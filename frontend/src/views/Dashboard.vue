<script setup>
import { ref, reactive, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";
import axios from "axios";

const router = useRouter();
const authStore = useAuthStore();

const tasks = ref([]);
const users = ref([]);
const isLoading = ref(false);
const isSaving = ref(false);
const dialog = ref(false);
const isEditMode = ref(false);
const currentId = ref(null);

const filters = reactive({
  status: "",
  priority: "",
});

const form = reactive({
  title: "",
  description: "",
  status: "pending",
  priority: "medium",
  due_date: "",
  assigned_user_id: null,
  file: null,
});

const headers = [
  { title: "Judul", key: "title" },
  { title: "Status", key: "status" },
  { title: "Prioritas", key: "priority" },
  { title: "Assigned To", key: "assigned_user" },
  { title: "Lampiran", key: "attachments" },
  { title: "Due Date", key: "due_date" },
  { title: "Aksi", key: "actions", sortable: false, align: "end" },
];

const fetchTasks = async () => {
  isLoading.value = true;
  try {
    const params = {};
    if (filters.status) params.status = filters.status;
    if (filters.priority) params.priority = filters.priority;

    const response = await axios.get("http://backend.test/api/tasks", {
      headers: { Authorization: `Bearer ${authStore.token}` },
      params,
    });
    tasks.value = response.data.data;
  } catch (error) {
    console.error("Gagal mengambil data task", error);
  } finally {
    isLoading.value = false;
  }
};

const fetchUsers = async () => {
  try {
    const response = await axios.get("http://backend.test/api/users", {
      headers: { Authorization: `Bearer ${authStore.token}` },
    });
    users.value = response.data;
  } catch (error) {
    console.error(error);
  }
};

const openCreateModal = () => {
  isEditMode.value = false;
  currentId.value = null;
  form.title = "";
  form.description = "";
  form.status = "pending";
  form.priority = "medium";
  form.due_date = "";
  form.assigned_user_id = null;
  form.file = null;
  dialog.value = true;
};

const openEditModal = (item) => {
  isEditMode.value = true;
  currentId.value = item.id;
  form.title = item.title;
  form.description = item.description || "";
  form.status = item.status;
  form.priority = item.priority;
  form.due_date = item.due_date || "";
  form.assigned_user_id = item.assigned_user_id || null;
  form.file = null;
  dialog.value = true;
};

const saveTask = async () => {
  isSaving.value = true;
  try {
    const headers = {
      Authorization: `Bearer ${authStore.token}`,
    };

    const taskData = {
      title: form.title,
      description: form.description || "",
      status: form.status,
      priority: form.priority,
      due_date: form.due_date || "",
    };
    if (form.assigned_user_id) {
      taskData.assigned_user_id = form.assigned_user_id;
    }

    let responseTask;
    if (isEditMode.value) {
      responseTask = await axios.put(`http://backend.test/api/tasks/${currentId.value}`, taskData, { headers });
    } else {
      responseTask = await axios.post("http://backend.test/api/tasks", taskData, { headers });
    }

    const taskId = isEditMode.value ? currentId.value : responseTask.data.id;

    if (form.file && taskId) {
      const fileFormData = new FormData();
      const uploadedFile = Array.isArray(form.file) ? form.file[0] : form.file;
      fileFormData.append("file", uploadedFile);

      await axios.post(`http://backend.test/api/tasks/${taskId}/attachments`, fileFormData, {
        headers: {
          Authorization: `Bearer ${authStore.token}`,
          "Content-Type": "multipart/form-data",
        },
      });
    }

    dialog.value = false;
    fetchTasks();
  } catch (error) {
    console.error("Gagal menyimpan task / upload file", error);
  } finally {
    isSaving.value = false;
  }
};

const deleteTask = async (id) => {
  if (!confirm("Yakin ingin menghapus task ini?")) return;
  try {
    await axios.delete(`http://backend.test/api/tasks/${id}`, {
      headers: { Authorization: `Bearer ${authStore.token}` },
    });
    fetchTasks();
  } catch (error) {
    console.error("Gagal menghapus task", error);
  }
};

const handleLogout = () => {
  authStore.logout();
  router.push("/login");
};

// Warna indikator status
const getStatusColor = (status) => {
  if (status === "completed") return "success";
  if (status === "in_progress") return "warning";
  return "grey";
};

const getPriorityColor = (priority) => {
  if (priority === "high") return "error";
  if (priority === "medium") return "warning";
  return "info";
};

onMounted(() => {
  fetchTasks();
  fetchUsers();
});
</script>

<template>
  <v-app>
    <!-- App Bar / Header -->
    <v-app-bar color="primary" flat>
      <v-app-bar-title class="font-weight-bold">Task Management Dashboard</v-app-bar-title>
      <template v-slot:append>
        <v-btn prepend-icon="mdi-logout" color="white" variant="text" @click="handleLogout"> Logout </v-btn>
      </template>
    </v-app-bar>

    <v-main class="bg-grey-lighten-4">
      <v-container class="py-6">
        <!-- Top Actions & Filters -->
        <v-row class="mb-4" align="center">
          <v-col cols="12" sm="4">
            <v-btn color="primary" prepend-icon="mdi-plus" block @click="openCreateModal"> Tambah Task Baru </v-btn>
          </v-col>
          <v-col cols="6" sm="4">
            <v-select v-model="filters.status" :items="['', 'pending', 'in_progress', 'completed']" label="Filter Status" variant="outlined" density="compact" hide-details clearable @update:model-value="fetchTasks"></v-select>
          </v-col>
          <v-col cols="6" sm="4">
            <v-select v-model="filters.priority" :items="['', 'low', 'medium', 'high']" label="Filter Prioritas" variant="outlined" density="compact" hide-details clearable @update:model-value="fetchTasks"></v-select>
          </v-col>
        </v-row>

        <!-- Task Table Card -->
        <v-card class="elevation-2 rounded-lg">
          <v-data-table :headers="headers" :items="tasks" :loading="isLoading" class="elevation-0" hide-default-footer>
            <!-- Kolom Status dengan Warna (Chip) -->
            <template v-slot:[`item.status`]="{ item }">
              <v-chip :color="getStatusColor(item.status)" size="small" class="text-uppercase font-weight-bold">
                {{ item.status }}
              </v-chip>
            </template>

            <!-- Kolom Prioritas -->
            <template v-slot:[`item.priority`]="{ item }">
              <v-chip :color="getPriorityColor(item.priority)" size="x-small" variant="outlined">
                {{ item.priority }}
              </v-chip>
            </template>

            <!-- Kolom Assigned User -->
            <template v-slot:[`item.assigned_user`]="{ item }">
              <span class="font-weight-medium">
                {{ item.assigned_user ? item.assigned_user.name : "Unassigned" }}
              </span>
            </template>

            <!-- Kolom Indikator Lampiran -->
            <!-- Kolom Indikator & Link Download Lampiran -->
            <template v-slot:[`item.attachments`]="{ item }">
              <div v-if="item.attachments && item.attachments.length > 0">
                <v-btn
                  v-for="file in item.attachments"
                  :key="file.id"
                  :href="`http://backend.test/api/attachments/${file.id}/download`"
                  target="_blank"
                  size="x-small"
                  color="primary"
                  variant="tonal"
                  prepend-icon="mdi-paperclip"
                  class="mr-1 mb-1"
                >
                  {{ file.file_name.length > 15 ? file.file_name.substring(0, 15) + "..." : file.file_name }}
                </v-btn>
              </div>
              <span v-else class="text-grey text-caption">Tidak ada</span>
            </template>

            <!-- Kolom Tombol Aksi -->
            <template v-slot:[`item.actions`]="{ item }">
              <v-btn icon="mdi-pencil" size="small" variant="text" color="info" @click="openEditModal(item)"></v-btn>
              <v-btn icon="mdi-delete" size="small" variant="text" color="error" @click="deleteTask(item.id)"></v-btn>
            </template>
          </v-data-table>
        </v-card>
      </v-container>
    </v-main>

    <!-- Dialog Form (Tambah / Edit Task) -->
    <v-dialog v-model="dialog" max-width="500px">
      <v-card class="rounded-lg">
        <v-card-title class="bg-primary text-white py-3">
          {{ isEditMode ? "Edit Task" : "Tambah Task Baru" }}
        </v-card-title>
        <v-card-text class="pt-4">
          <v-form @submit.prevent="saveTask">
            <v-text-field v-model="form.title" label="Judul Task" variant="outlined" density="comfortable" class="mb-3" required></v-text-field>

            <v-textarea v-model="form.description" label="Deskripsi" variant="outlined" density="comfortable" class="mb-3"></v-textarea>

            <v-row>
              <v-col cols="6">
                <v-select v-model="form.status" :items="['pending', 'in_progress', 'completed']" label="Status" variant="outlined" density="comfortable"></v-select>
              </v-col>
              <v-col cols="6">
                <v-select v-model="form.priority" :items="['low', 'medium', 'high']" label="Prioritas" variant="outlined" density="comfortable"></v-select>
              </v-col>
            </v-row>

            <v-text-field v-model="form.due_date" label="Tenggat Waktu (Due Date)" type="date" variant="outlined" density="comfortable" class="mb-3"></v-text-field>

            <v-select v-model="form.assigned_user_id" :items="users" item-title="name" item-value="id" label="Assigned User (Ditugaskan ke)" variant="outlined" density="comfortable" class="mb-3" clearable></v-select>

            <v-file-input v-model="form.file" label="Upload Lampiran (Opsional)" variant="outlined" density="comfortable" prepend-icon="mdi-paperclip" class="mb-3"></v-file-input>

            <div class="d-flex justify-end gap-2 mt-4">
              <v-btn color="grey-darken-1" variant="text" @click="dialog = false">Batal</v-btn>
              <v-btn color="primary" type="submit" :loading="isSaving">Simpan</v-btn>
            </div>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-app>
</template>
