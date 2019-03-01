<template>
  <v-content>
    <v-container fluid fill-height justify-center>
      <v-layout row wrap pt-5>
        <v-flex xs12 lg6 md6 fill-height pt-5 justify-center>
          <div class="text-xs-center">
            <div class="display-3">
              <span class="display-4 px-2 py-1 B-logo">B</span>INZO
            </div>
            <div class="display-1 mt-5">GET IN TOUCH</div>
            <blockquote
              class="blockquote"
            >WE KEEP YOU IN TOUCH WITH ALL YOUR FRIENDS AND HELP YOU TO SHARE YOUR QUOTES AND OPINIONS WITH THEM</blockquote>
          </div>
        </v-flex>
        <v-flex xs12 lg4 md6 offset-lg1 pt-5>
          <v-card class="white--text text-xs-center">
            <div class="pa-2 form-title title">Sign In</div>
            <v-form lazy-validation ref="login_form" class="pa-3" v-model="login_form">
              <v-text-field
                box
                placeholder="enter your email"
                v-model="email"
                :rules="emailRules"
                label="E-mail"
              ></v-text-field>
              <v-text-field
                box
                placeholder="enter your password"
                :rules="passwordRules"
                label="Password"
                v-model="password"
              ></v-text-field>
              <v-btn color="orange lighten-1" @click="login">Login</v-btn>
              <v-flex d-inline-block ml-2 class="subheading">Dont have an account?
                <router-link class="have-account-link body-1" to="/register">sign up</router-link>
              </v-flex>
            </v-form>
          </v-card>
        </v-flex>
      </v-layout>
      <v-snackbar v-model="snackbar" :color="snackbar_color" bottom left>
        {{snackbar_message}}
        <v-btn color="white" flat @click="snackbar = false">Close</v-btn>
      </v-snackbar>
    </v-container>
  </v-content>
</template>

<script>
// @ is an alias to /src
import axios from "axios";

export default {
  name: "login",
  data() {
    return {
      snackbar: false,
      snackbar_message: "",
      snackbar_color: "",
      login_form: true,
      email: "",
      emailRules: [
        v => !!v || "Email is required",
        v => (v && v.length <= 254) || "Email must be less than 255 characters"
      ],
      password: "",
      passwordRules: [
        v => !!v || "Password is required",
        v =>
          (v && v.length <= 100) || "Password must be less than 100 characters"
      ]
    };
  },
  created() {},
  methods: {
    login() {
      if (!this.$refs.login_form.validate()) {
        this.showSnackbar("please enter your email and password", "red");
        return;
      }
      console.log("logged in");
    },
    showSnackbar(message, color) {
      this.snackbar = true;
      this.snackbar_message = message;
      this.snackbar_color = color;
    }
  }
};
</script>
<style>
.B-logo {
  background-color: #ffa726;
  border-radius: 3px;
}
.form-title {
  background-color: #ffa726;
  box-shadow: 0px 3px 1px 1px #25252542;
}
.have-account-link {
  color: #ffa726;
}
</style>