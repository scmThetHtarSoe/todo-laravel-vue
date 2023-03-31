<template>
  <div>
    <h2 class="title text-3xl">Register Form</h2>
    <form @submit.prevent="signUp()">
      <input
        type="text"
        name="name"
        v-model="name"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mt-4 mb-2"
        placeholder="Please Enter Your Name"
      />
      <span class="text-red-500" v-if="errMsg.name">{{ errMsg.name[0] }}</span>
      <input
        type="email"
        name="email"
        v-model="email"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mt-4 mb-2"
        placeholder="Please Enter Your Email"
      />
      <span class="text-red-500" v-if="errMsg.email">{{
        errMsg.email[0]
      }}</span>
      <input
        type="password"
        name="password"
        v-model="password"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline my-4"
        placeholder="Please Enter Your Password"
      />
      <span class="text-red-500" v-if="errMsg.password">{{
        errMsg.password[0]
      }}</span>
      <div class="flex justify-between">
        <span class="cursor-pointer text-blue-400"
          ><router-link to="/login"
            >Already have an account?Sign In</router-link
          ></span
        >
        <button
          type="submit"
          class="bg-blue-400 px-4 py-1 text-md text-white ml-2 rounded-md"
          @click="signUp()"
        >
          Sign Up
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import axios from "axios";
import router from "@/router";

export default {
  name: "Register",
  data() {
    return {
      name: "",
      email: "",
      password: "",
      errMsg: [],
    };
  },
  methods: {
    signUp() {
      axios
        .post("http://localhost:8000/api/register", {
          name: this.name,
          email: this.email,
          password: this.password,
        })
        .then((response) => {
          if (response.data.msg == "User Created Successfully!!!") {
            this.name = this.email = this.password = "";
            this.$router.push("/login");
            localStorage.setItem("msg", response.data.msg);
          } else {
            this.errMsg = response.data;
          }
        });
      this.errMsg = [];
    },
  },
  router,
};
</script>

<style lang="stylus" scoped></style>
