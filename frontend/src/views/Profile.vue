<template>
  <v-layout>
    <v-flex xs12>
      <v-flex v-if="posts.length==0" text-xs-center display-1 py-5>
        you didn't Have any posts yet
      </v-flex>
      <v-flex>
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
            :deletable="post.deletable"
            @like-trigger="likeTrigger"
            @delete-post="postDeleted"
          ></postCard>
        </v-flex>
        <v-snackbar v-model="snackbar" :color="snackbar_color" bottom left>
          {{snackbar_message}}
          <v-btn color="white" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
      </v-flex>
      <v-flex text-xs-center v-if="loading">
        <v-progress-circular :size="50" color="amber" indeterminate></v-progress-circular>
      </v-flex>
    </v-flex>
  </v-layout>
</template>
<script>
export default {
  components: {
    postCard: () => import("@/components/core/postCard")
  },
  name: "profile",
  data() {
    return {
      snackbar: false,
      snackbar_message: "",
      snackbar_color: "",
      posts_page: 1,
      posts: [],
      loading: false,
      stop_loading: false
    };
  },
  created() {
    this.getPosts();
    this.scrollListener();
  },
  methods: {
    scrollListener() {
      window.addEventListener("scroll", this.scroll);
    },
    getPosts() {
      this.posts_page != 1 ? (this.loading = true) : "";
      let token = this.$cookies.get("Utoken");
      this.$http
        .get(
          `http://localhost/binzo/backend/apis/user/getProfile.php?page=${
            this.posts_page
          }`,
          {
            headers: {
              Authorization: "Bearer " + token
            }
          }
        )
        .then(response => {
          let code = response.data.code;
          if (code !== 200) {
            this.showSnackbar("something went wrong!", "red");
            return;
          }
          if (response.data.posts.length !== 0) {
            let posts = this.posts;
            let new_posts = response.data.posts.data;
            console.log(new_posts);
            this.posts = posts.concat(new_posts);
            this.posts_page++;
          } else {
            this.stop_loading = true;
          }
          this.loading = false;
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
          !this.stop_loading ? this.getPosts() : "";
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
    postDeleted(data) {
      let code = data.response.data.code;
      let message = data.response.data.message;
      if (code == 200) {
        let deleted_post_id = data.post_id;
        Array.prototype.forEach.call(this.posts, (e, index) => {
          if (e.id == deleted_post_id) {
            this.posts.splice(index, 1);
          }
        });
        this.showSnackbar(message, "green");
      } else {
        this.showSnackbar(message, "red");
      }
    }
  }
};
</script>
<style>
</style>


