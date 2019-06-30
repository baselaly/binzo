<template>
  <v-flex xs12>
    <v-card class="white--text text-xs-center">
      <v-form lazy-validation ref="edit_form" class="pa-3" v-model="edit_form">
        <v-avatar class="my-3 ml-4" size="130">
          <img :src="image" class="nav-profile-img">
        </v-avatar>
        <label for="file-upload" class="custom-file-upload ml-4">
          <v-icon>edit</v-icon>
        </label>
        <input id="file-upload" @change="getUserImage($event)" type="file">
        <v-flex display-1 text-truncate py-3 text-xs-center>{{user.first_name}} {{user.last_name}}</v-flex>
        <v-text-field
          box
          placeholder="enter your email"
          v-model="user.email"
          :rules="requiredRules"
          label="E-mail"
        ></v-text-field>
        <v-text-field
          box
          placeholder="enter your first name"
          v-model="user.first_name"
          :rules="requiredRules"
          label="First Name"
        ></v-text-field>
        <v-text-field
          box
          placeholder="enter your last name"
          v-model="user.last_name"
          :rules="requiredRules"
          label="Last Name"
        ></v-text-field>
        <v-text-field
          box
          placeholder="enter your country"
          v-model="user.country"
          :rules="requiredRules"
          label="Country"
        ></v-text-field>
        <v-text-field
          box
          placeholder="enter your city"
          v-model="user.city"
          :rules="requiredRules"
          label="City"
        ></v-text-field>
        <v-text-field
          box
          placeholder="enter your password"
          :rules="requiredRules"
          label="Your Password"
          v-model="user.password"
          :append-icon="show_password ? 'visibility_off' : 'visibility'"
          :type="show_password ? 'text' : 'password'"
          @click:append="show_password = !show_password"
        ></v-text-field>
        <v-btn color="orange lighten-1" @click="editProfile">Save</v-btn>
      </v-form>
    </v-card>
    <v-snackbar v-model="snackbar" :color="snackbar_color" bottom left>
      {{snackbar_message}}
      <v-btn color="white" flat @click="snackbar = false">Close</v-btn>
    </v-snackbar>
  </v-flex>
</template>
<script>
export default {
  name: "profile",
  data() {
    return {
      edit_form: true,
      snackbar: false,
      snackbar_message: "",
      snackbar_color: "",
      requiredRules: [v => !!v || "field is required"],
      user: {
        email: "",
        password: "",
        first_name: "",
        last_name: "",
        country: "",
        city: "",
        image: ""
      },
      image: null,
      show_password: false
    };
  },
  created() {
    this.getUser();
  },
  methods: {
    getUser() {
      let token = this.$cookies.get("Utoken");
      this.$http
        .get("http://localhost/binzo/backend/apis/user/getUserInfo.php", {
          headers: {
            Authorization: "Bearer " + token
          }
        })
        .then(response => {
          this.user = response.data.user;
          this.image = this.user.image;
          console.log(this.user);
        })
        .catch(error => {
          this.showSnackbar("something went wrong!", "red");
        });
    },
    getUserImage(event) {
      var image = event.target.files[0];
      if (image != "" && image != null && image != undefined) {
        var type = image.type;
        if (
          type != "image/png" &&
          type != "image/jpg" &&
          type != "image/jpeg"
        ) {
          this.showSnackbar("please choose valid image", "red");
        } else {
          this.showImage(image);
          this.user.image = image;
        }
      } else {
        this.showSnackbar("please choose valid image", "red");
      }
    },
    showImage(image) {
      var file = new Image();
      var reader = new FileReader();

      reader.onload = event => {
        this.image = event.target.result;
      };
      reader.readAsDataURL(image);
    },
    editProfile() {
      if (!this.$refs.edit_form.validate()) {
        this.showSnackbar("please complete your fields", "red");
        return;
      }
      let token = this.$cookies.get("Utoken");
      let user_data = new FormData();

      user_data.append("first_name", this.user.first_name);
      user_data.append("last_name", this.user.last_name);
      user_data.append("country", this.user.country);
      user_data.append("city", this.user.city);
      user_data.append("email", this.user.email);
      user_data.append("password", this.user.password);
      user_data.append("image", this.user.image);

      console.log(this.user);
      this.$http
        .post(
          "http://localhost/binzo/backend/apis/user/editprofile.php",
          user_data,
          {
            headers: {
              Authorization: "Bearer " + token
            }
          }
        )
        .then(response => {
          let code = response.data.code;
          let message = response.data.message;
          if (code == 200) {
            this.showSnackbar(message, "green");
          } else {
            this.showSnackbar(message, "red");
          }
        })
        .catch(error => {
          this.showSnackbar("Something went wrong!", "red");
        });
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
input[type="file"] {
  display: none;
}
.custom-file-upload {
  display: inline-block;
  cursor: pointer;
}
.nav-profile-img {
  box-shadow: 0px 2px 20px 2px #000000;
}
</style>