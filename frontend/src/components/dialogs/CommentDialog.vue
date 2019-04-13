<template>
  <div class="text-xs-center">
    <v-dialog v-model="dialog" width="500" persistent>
      <v-card>
        <v-card-title class="headline" primary-title>Add Comment</v-card-title>

        <v-card-text>
          <v-form lazy-validation ref="comment_form" class="pa-3" v-model="comment_form">
            <v-textarea outline :rules="rules" label="Enter Your Comment" v-model="comment"></v-textarea>
          </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="sendComment">Send</v-btn>
          <v-btn color="red" @click="closeCommentDialog">Close</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
export default {
  data() {
    return {
      comment: "",
      comment_form: true,
      rules: [v => !!v || "comment is required"]
    };
  },
  props: {
    dialog: {
      required: true,
      type: Boolean
    },
    post_id: {
      required: true,
      type: Number
    }
  },
  methods: {
    closeCommentDialog() {
      this.$emit("close-comment-dialog", {
        state: false,
        post_id: this.post_id
      });
    },
    sendComment() {
      if (!this.$refs.comment_form.validate()) {
        return;
      }
      let token = this.$cookies.get("Utoken");
      var comment = { comment: this.comment, post_id: this.post_id };
      this.$http
        .post(
          "http://localhost/binzo/backend/apis/comment/addComment.php",
          comment,
          {
            headers: {
              Authorization: "Bearer " + token
            }
          }
        )
        .then(response => {
          let code = response.data.code;
          if (code == 200) {
            this.$emit("close-comment-dialog", {
              state: true,
              post_id: this.post_id
            });
            this.$refs.comment_form.reset();
          }
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>