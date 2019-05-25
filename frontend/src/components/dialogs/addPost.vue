<template>
  <div class="text-xs-center">
    <v-dialog v-model="dialog" width="800" persistent>
      <v-card>
        <v-card-title class="headline" primary-title>Add Post</v-card-title>

        <v-card-text>
          <v-form lazy-validation ref="post_form" class="pa-3" v-model="post_form">
            <v-textarea outline :rules="rules" label="Whats on your mind?" v-model="body"></v-textarea>
            <img class="rendered_image" :src="image">
            <label for="file-upload" class="custom-file-upload">
              <i class="fa fa-cloud-upload"></i> Attach an image
            </label>
            <input id="file-upload" @change="getUserImage($event)" type="file">
          </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="addPost">Post</v-btn>
          <v-btn color="red" @click="closeAddDialog">Close</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
export default {
  data() {
    return {
      body: "",
      post_form: true,
      rules: [v => !!v || "body is required"],
      image: "",
      postImage: ""
    };
  },
  props: {
    dialog: {
      required: true,
      type: Boolean
    }
  },
  methods: {
    closeAddDialog() {
      this.$emit("close-add-post-dialog", {
        state: true
      });
      this.$refs.post_form.reset();
      this.image = "";
    },
    addPost() {
      if (!this.$refs.post_form.validate()) {
        return;
      }
      let token = this.$cookies.get("Utoken");
      let form_data = new FormData();
      form_data.append("body", this.body);
      form_data.append("image", this.postImage);

      this.$http
        .post("http://localhost/binzo/backend/apis/post/add.php", form_data, {
          headers: {
            Authorization: "Bearer " + token
          }
        })
        .then(response => {
          console.log(response);
          let code = response.data.code;
          if (code == 200) {
            this.$emit("close-add-post-dialog", {
              state: true,
              message: false
            });
            this.$refs.post_form.reset();
            this.image = "";
          } else {
            let message = response.data.message;
            this.$emit("close-add-post-dialog", {
              state: false,
              message: message
            });
            this.$refs.post_form.reset();
            this.image = "";
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
    getUserImage(event) {
      this.postImage = "";
      var image = event.target.files[0];
      if (image != "" && image != null && image != undefined) {
        var type = image.type;
        if (
          type != "image/png" &&
          type != "image/jpg" &&
          type != "image/jpeg"
        ) {
          this.$emit("close-add-post-dialog", {
            state: false,
            message: "please choose valid image"
          });
        } else {
          this.showImage(image);
          this.postImage = image;
        }
      } else {
        this.$emit("close-add-post-dialog", {
          state: false,
          message: "please choose valid image"
        });
      }
    },
    showImage(image) {
      var file = new Image();
      var reader = new FileReader();

      reader.onload = event => {
        this.image = event.target.result;
      };
      reader.readAsDataURL(image);
    }
  }
};
</script>
<style>
.rendered_image {
  width: 700px;
}
input[type="file"] {
  display: none;
}
.custom-file-upload {
  border: 1px solid #ccc;
  display: inline-block;
  padding: 6px 12px;
  cursor: pointer;
}
</style>