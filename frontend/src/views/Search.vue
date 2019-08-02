<template>
  <v-layout row wrap>
    <v-flex
      v-if="users.length==0 && users_loading==false"
      text-xs-center
      title
      py-5
    >No Results Found for your search {{ search_keyword }}</v-flex>
    <v-flex xs12 md3 ma-2 d-inline-block text-xs-center v-for="(user,i) in users" :key="i">
      <v-card class="pa-3" height="270">
        <v-avatar size="80">
          <v-img class="user-image" :src="user.image" aspect-ratio="2.75"></v-img>
        </v-avatar>
        <v-card-title class="text-truncate justify-center" primary-title>
          <div>
            <h3 class="headline mb-0">{{user.full_name}}</h3>
            <div
              class="pa-2 stats-div"
            >Followers: {{user.statistics.followers}} Followings: {{user.statistics.followings}}</div>
          </div>
        </v-card-title>

        <v-card-actions class="text-xs-center">
          <v-btn
            v-if="user.follow"
            class="follow_btn"
            color="#ffa726"
            @click="follow(user.id)"
          >follow</v-btn>
          <v-btn
            @mouseover="user.hover=true"
            @mouseleave="user.hover=false"
            @click="follow(user.id)"
            v-else
            class="following_btn"
            :outline="!user.hover"
            :color="!user.hover?'#ffa726':'red'"
          >
            {{user.hover?'unfollow':'following'}}
            <v-icon v-if="!user.hover" right dark>done</v-icon>
            <v-icon v-if="user.hover" right dark>clear</v-icon>
          </v-btn>
        </v-card-actions>
      </v-card>
      <v-flex text-xs-center py-5 v-if="users_loading">
        <v-progress-circular :size="50" color="amber" indeterminate></v-progress-circular>
      </v-flex>
    </v-flex>
    <v-snackbar v-model="snackbar" :color="snackbar_color" bottom left>
      {{snackbar_message}}
      <v-btn color="white" flat @click="snackbar = false">Close</v-btn>
    </v-snackbar>
  </v-layout>
</template>

<script>
export default {
  name: "search",
  data() {
    return {
      snackbar: false,
      snackbar_message: "",
      snackbar_color: "",
      users: [],
      users_page: 1,
      users_loading: true,
      search_keyword: "",
      load_more: true,
      stop_loading: false
    };
  },
  created() {
    this.getUsers();
    this.scrollListener();
  },
  methods: {
    scrollListener() {
      window.addEventListener("scroll", this.scroll);
    },
    getUsers() {
      this.search_keyword = this.$route.query.q;
      let token = this.$cookies.get("Utoken");
      this.$http
        .get(
          `http://localhost/binzo/backend/apis/user/search.php?q=${this.search_keyword}&page=${this.users_page}`,
          {
            headers: {
              Authorization: "Bearer " + token
            }
          }
        )
        .then(response => {
          let users = this.users;
          let new_users = response.data.users;
          this.users = users.concat(new_users);
          this.load_more = new_users.length == 0 ? false : true;
          this.users_loading = false;
          console.log(this.users);
        })
        .catch(error => {
          console.log(error);
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
            Array.prototype.forEach.call(this.users, e => {
              if (e.id == user_id) {
                e.follow = !e.follow;
              }
            });
          }
        })
        .catch(error => {
          this.showSnackbar("something went wrong!", "red");
        });
    },
    loadMoreUsers() {
      this.users_page += 1;
      this.users_loading = true;
      this.getUsers();
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
          this.load_more ? this.loadMoreUsers() : "";
        }
      };
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
.follow_btn {
  position: absolute !important;
  bottom: 20px;
  left: 35%;
}
.following_btn {
  position: absolute !important;
  bottom: 20px;
  left: 20%;
  width: 150px;
}
.stats-div {
  color: #ffa726;
}
.user-image {
  border: 2px solid white;
}
.unfollow {
  background-color: red;
}
</style>