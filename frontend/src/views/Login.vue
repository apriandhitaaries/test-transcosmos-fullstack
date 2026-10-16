<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";

const email = ref("");
const password = ref("");
const errorMessage = ref("");
const isLoading = ref(false);

const router = useRouter();
const authStore = useAuthStore();

const handleLogin = async () => {
  isLoading.value = true;
  errorMessage.value = "";

  try {
    await authStore.login(email.value, password.value);
    router.push("/");
  } catch (error) {
    errorMessage.value = error.response?.data?.message || "Login gagal, periksa koneksi atau kredensial.";
  } finally {
    isLoading.value = false;
  }
};
</script>

<template>
  <v-app>
    <v-main class="bg-grey-lighten-4">
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center" class="fill-height">
          <v-col cols="12" sm="8" md="4" lg="3">
            <v-card class="elevation-6 rounded-lg pa-4">
              <v-card-title class="text-h5 font-weight-bold text-center text-primary mb-4"> Task Management </v-card-title>

              <v-card-text>
                <v-form @submit.prevent="handleLogin">
                  <v-text-field v-model="email" label="Email" prepend-inner-icon="mdi-account" type="email" variant="outlined" density="comfortable" class="mb-2" required></v-text-field>

                  <v-text-field v-model="password" label="Password" prepend-inner-icon="mdi-lock" type="password" variant="outlined" density="comfortable" class="mb-2" required></v-text-field>

                  <v-alert v-if="errorMessage" type="error" class="mb-4" density="compact" variant="tonal">
                    {{ errorMessage }}
                  </v-alert>

                  <v-btn type="submit" color="primary" size="large" block :loading="isLoading" class="mt-2"> Login </v-btn>
                </v-form>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>
