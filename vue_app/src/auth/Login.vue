<template>
  <div>
    <h2 class="title text-3xl">Login Form</h2>
    <form @submit.prevent="login()">
      <input
        type="email"
        name="email"
        v-model="email"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mt-4 mb-2"
        placeholder="Please Enter Your Email"
      />
      <span class="text-red-500" v-if="errorMsg.email">{{
        errorMsg.email[0]
      }}</span>
      <input
        type="password"
        name="password"
        v-model="password"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline my-4"
        placeholder="Please Enter Your Password"
      />
      <span class="text-red-500" v-if="errorMsg.password">{{
        errorMsg.password[0]
      }}</span>
      <div class="flex justify-between">
        <span class="cursor-pointer text-blue-400"
          ><router-link to="/register"
            >Didn't have an account?Sign Up</router-link
          ></span
        >
        <button
          type="submit"
          @click="login()"
          class="bg-blue-400 px-4 py-1 text-md text-white ml-2 rounded-md"
        >
          Login
        </button>
      </div>
      <span class="text-red-500">{{ error }}</span>
    </form>
  </div>
</template>

<script>
export default {
  name: "Login",
  data() {
    return {
      email: "",
      password: "",
      errorMsg: [],
      error: "",
    };
  },
  methods: {
    login() {
      axios
        .post("http://localhost:8000/api/login", {
          email: this.email,
          password: this.password,
        })
        .then((response) => {
          if (response.data.msg == "User Login Successfully!!!") {
            let token = response.data.access_token;
            sessionStorage.setItem("token", token);
            this.$router.push("/");
          } else {
            if (response.data.msg == "Incorrect Password!!!") {
              this.error = response.data.msg;
            } else if (response.data.msg == "Invalid User!!!") {
              this.error = response.data.msg;
            } else {
              var getmsg = {
                email: response.data.email,
                password: response.data.password,
              };
              this.errorMsg = getmsg;
            }
          }
        });
      this.error = "";
      this.errorMsg = [];
    },
  },
};
</script>

<style lang="stylus" scoped></style>
