<template>
  <v-card color="rgb(33, 33, 33)" dark class="ma-3">
    <v-card-title>
      <v-list-tile class="grow">
        <v-list-tile-avatar color="grey darken-3" class="mr-2">
          <v-avatar size="65" class="elevation-6">
            <img :src="owner_image" alt="avatar">
          </v-avatar>
        </v-list-tile-avatar>

        <v-list-tile-content>
          <v-list-tile-title>{{owner_name}}</v-list-tile-title>
        </v-list-tile-content>
        <v-spacer></v-spacer>
        <v-icon v-if="deletable" @click="deletePost(id)" class="mr-1 delete-icon">delete</v-icon>
      </v-list-tile>
    </v-card-title>
    <router-link :to="{ name: 'post', params: {id: id } }" class="white--text post-link">
      <v-card-text class="subheading font-weight-light">
        {{body}}
        <v-img v-if="post_image!=null" class="my-4" :src="post_image"></v-img>
      </v-card-text>
    </router-link>

    <v-card-actions>
      <v-list-tile class="grow">
        <v-layout align-center>
          <v-icon
            @click="like(id)"
            class="mr-1 like-icon"
          >{{liked ? 'favorite' : 'favorite_border'}}</v-icon>
          <span class="subheading mr-2 like-span">{{likes_count}}</span>
          <v-icon @click="showCommentDialog" class="mr-1 comment-icon">add_comment</v-icon>
          <span class="subheading comment-icon">{{comments_count}}</span>
        </v-layout>
      </v-list-tile>
    </v-card-actions>
    <commentDialog :dialog="commentDialog" :post_id="id" @close-comment-dialog="closeCommentDialog"></commentDialog>
  </v-card>
</template>

<script>
// @ is an alias to /src

export default {
  name: "postCard",
  components: {
    commentDialog: () => import("@/components/dialogs/CommentDialog")
  },
  data() {
    return {
      commentDialog: false
    };
  },
  props: {
    id: {
      required: true,
      type: Number
    },
    user_id: {
      required: true,
      type: Number
    },
    owner_image: {
      required: true,
      type: String
    },
    owner_name: {
      required: true,
      type: String
    },
    post_image: {
      required: true
    },
    body: {
      required: true,
      type: String
    },
    liked: {
      required: true,
      type: Boolean
    },
    likes_count: {
      required: true,
      type: Number
    },
    comments_count: {
      required: true,
      type: Number
    },
    deletable: {
      required: true,
      type: Boolean
    }
  },
  methods: {
    like(post_id) {
      let token = this.$cookies.get("Utoken");
      this.$http
        .get(
          `http://localhost/binzo/backend/apis/like/like.php?id=${post_id}`,
          {
            headers: {
              Authorization: "Bearer " + token
            }
          }
        )
        .then(response => {
          this.$emit("like-trigger", post_id);
        })
        .catch(error => {
          console.log(error);
        });
    },
    showCommentDialog() {
      this.commentDialog = true;
    },
    closeCommentDialog(value) {
      if (value.state == true) {
        this.$emit("comment-added", value.post_id);
      }
      this.commentDialog = false;
    },
    deletePost(post_id) {
      console.log(post_id);
      let token = this.$cookies.get("Utoken");
      this.$http
        .get(
          `http://localhost/binzo/backend/apis/post/delete.php?id=${post_id}`,
          {
            headers: {
              Authorization: "Bearer " + token
            }
          }
        )
        .then(response => {
          let data = {
            response: response,
            post_id: post_id
          };
          this.$emit("delete-post", data);
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>
<style>
.post-link {
  text-decoration: none;
}
.like-icon {
  color: #f34747 !important;
}
.like-span {
  color: #f34747 !important;
}
.comment-icon {
  color: #ffa726 !important;
}
</style>
