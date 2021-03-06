<template>
  <v-layout>
    <v-flex xs12 md8>
      <v-flex v-if="posts.length==0" text-xs-center display-1 py-5>
        <v-btn color="#ffa726" @click="OpenAddPostDialog">Add Your First Post</v-btn>
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
            @comment-added="commentAdded"
            @delete-post="postDeleted"
          ></postCard>
        </v-flex>
        <v-btn color="#ffa726" small fab fixed top right @click="OpenAddPostDialog">
          <v-icon>add</v-icon>
        </v-btn>
        <v-snackbar v-model="snackbar" :color="snackbar_color" bottom left>
          {{snackbar_message}}
          <v-btn color="white" flat @click="snackbar = false">Close</v-btn>
        </v-snackbar>
        <addPost :dialog="addPostDialog" @close-add-post-dialog="closePostDialog"></addPost>
      </v-flex>
      <v-flex text-xs-center v-if="loading">
        <v-progress-circular :size="50" color="amber" indeterminate></v-progress-circular>
      </v-flex>
    </v-flex>
    <v-flex md4 text-xs-center class="hidden-sm-and-down">
      <v-list subheader>
        <v-subheader class="headline">Who to Follow</v-subheader>
        <v-flex text-xs-center v-if="similar_users_loading">
          <v-progress-circular :size="40" color="amber" indeterminate></v-progress-circular>
        </v-flex>
        <v-flex v-else>
          <v-flex v-if="similar_users.length==0" text-xs-center title py-5>No Recommendations</v-flex>
          <v-list-tile v-for="(user,i) in similar_users" :key="i" avatar class="pa-2">
            <v-list-tile-avatar>
              <img :src="user.image" />
            </v-list-tile-avatar>

            <v-list-tile-content>
              <v-list-tile-title>{{user.first_name + user.last_name}}</v-list-tile-title>
            </v-list-tile-content>

            <v-list-tile-action>
              <v-btn outline color="#ffa726" @click="follow(user.id)">follow</v-btn>
            </v-list-tile-action>
          </v-list-tile>
        </v-flex>
      </v-list>
    </v-flex>
  </v-layout>
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
      addPostDialog: false,
      loading: false,
      stop_loading: false,
      similar_users: [],
      similar_users_loading: true
    };
  },
  created() {
    this.getPosts();
    this.getSimilarUsers();
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
          `http://localhost/binzo/backend/apis/user/getHome.php?page=${this.posts_page}`,
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
            let new_posts = response.data.posts;
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
    getSimilarUsers() {
      this.similar_users_loading = true;
      let token = this.$cookies.get("Utoken");
      this.$http
        .get("http://localhost/binzo/backend/apis/user/getSimilarUsers.php", {
          headers: {
            Authorization: "Bearer " + token
          }
        })
        .then(response => {
          this.similar_users = response.data.users;
          this.similar_users_loading = false;
        })
        .catch(error => {
          this.showSnackbar("something went wrong!", "red");
        });
    },
    follow(user_id) {
      let token = this.$cookies.get("Utoken");
      this.$http
        .get(
          `http://localhost/binzo/backend/apis/user/follow.php?id=${user_id}`,
          {
            headers: {
              Authorization: "Bearer " + token
            }
          }
        )
        .then(response => {
          let code = response.data.code;
          if (code != 200) {
            let message = response.data.message;
            this.showSnackbar(message, "red");
          } else {
            this.getSimilarUsers();
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
    },
    OpenAddPostDialog() {
      this.addPostDialog = true;
    },
    closePostDialog(data) {
      if (data.state == true && data.message == false) {
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
