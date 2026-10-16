import { defineStore } from "pinia";
import axios from "axios";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    token: localStorage.getItem("token") || null,
    user: null,
  }),
  actions: {
    async login(email, password) {
      try {
        const response = await axios.post("http://backend.test/api/auth/login", {
          email,
          password,
        });

        this.token = response.data.access_token;
        this.user = response.data.user;

        localStorage.setItem("token", this.token);

        axios.defaults.headers.common["Authorization"] = `Bearer ${this.token}`;
      } catch (error) {
        throw error;
      }
    },
    logout() {
      this.token = null;
      this.user = null;
      localStorage.removeItem("token");
      delete axios.defaults.headers.common["Authorization"];
    },
  },
});
