<template>
  <v-flex xs12>
    <v-flex v-for="(post, i) in posts" :key="i" ma-2>
      <postCard
        :id="post.id"
        :body="post.body"
        :user_id="post.user_id"
        :owner_image="post.user_image"
        :owner_name="post.fullname"
        :post_image="post.image"
        :liked="post.liked"
        :likes_count="post.likes_count"
        :comments_count="post.comments_count"
        @like-trigger="likeTrigger"
        @comment-added="commentAdded"
      ></postCard>
    </v-flex>
    <v-btn color="#ffa726" fab fixed top right @click="OpenAddPostDialog">
      <v-icon>add</v-icon>
    </v-btn>
    <v-snackbar v-model="snackbar" :color="snackbar_color" bottom left>
      {{snackbar_message}}
      <v-btn color="white" flat @click="snackbar = false">Close</v-btn>
    </v-snackbar>
    <addPost :dialog="addPostDialog" @close-add-post-dialog="closePostDialog"></addPost>
  </v-flex>
</template>

<script>
// @ is an alias to /src

export default {
  name: "home",
  components: {
    postCard: () => import("@/components/core/postCard"),
    addPost: () => import("@/components/dialogs/addPost")
  },
  data() {
    return {
      snackbar: false,
      snackbar_message: "",
      snackbar_color: "",
      posts_page: 1,
      posts: [],
      addPostDialog: false
    };
  },
  mounted() {
    this.scroll();
  },
  created() {
    this.getPosts();
  },
  methods: {
    getPosts() {
      let token = this.$cookies.get("Utoken");
      this.$http
        .get(
          `http://localhost/binzo/backend/apis/user/getHome.php?page=${
            this.posts_page
          }`,
          {
            headers: {
              Authorization: "Bearer " + token
            }
          }
        )
        .then(response => {
          console.log(response);
          let code = response.data.code;
          if (code !== 200) {
            this.showSnackbar("something went wrong!", "red");
            return;
          }
          if (response.data.posts.length !== 0) {
            let posts = this.posts;
            let new_posts = response.data.posts;
            this.posts = posts.concat(new_posts);
            this.posts_page++;
          }
        })
        .catch(error => {
          this.showSnackbar("something went wrong!", "red");
        });
    },
    scroll() {
      window.onscroll = () => {
        let bottomOfWindow =
          Math.max(
            window.pageYOffset,
            document.documentElement.scrollTop,
            document.body.scrollTop
          ) +
            window.innerHeight ===
          document.documentElement.offsetHeight;

        if (bottomOfWindow) {
          this.getPosts();
        }
      };
    },
    showSnackbar(message, color) {
      this.snackbar = true;
      this.snackbar_message = message;
      this.snackbar_color = color;
    },
    likeTrigger(post_id) {
      Array.prototype.forEach.call(this.posts, e => {
        if (e.id == post_id) {
          e.liked = !e.liked;
          e.liked == true ? e.likes_count++ : e.likes_count--;
        }
      });
    },
    commentAdded(post_id) {
      Array.prototype.forEach.call(this.posts, e => {
        if (e.id == post_id) {
          e.comments_count++;
        }
      });
    },
    OpenAddPostDialog() {
      this.addPostDialog = true;
    },
    closePostDialog(data) {
      if (data.state == true && data.message) {
        this.posts_page = 1;
        this.posts = [];
        this.getPosts();
        this.addPostDialog = false;
        this.showSnackbar("Posted", "green");
      } else if (data.state == false) {
        this.showSnackbar(data.message, "red");
      } else {
        this.addPostDialog = false;
      }
    }
  }
};
</script>
