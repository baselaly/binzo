<template>
  <v-flex xs12>
    <v-flex ma-2>
      <v-card color="rgb(33, 33, 33)" dark class="ma-3">
        <v-card-title>
          <v-list-tile class="grow">
            <v-list-tile-avatar color="grey darken-3" class="mr-2">
              <v-avatar size="65" class="elevation-6">
                <img :src="post.owner_image" alt="avatar">
              </v-avatar>
            </v-list-tile-avatar>

            <v-list-tile-content>
              <v-list-tile-title>{{post.owner_name}}</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-card-title>
        <v-card-text class="subheading font-weight-light">
          {{post.body}}
          <v-img v-if="post.image!=null" class="my-4" :src="post.image"></v-img>
        </v-card-text>

        <v-card-actions>
          <v-list-tile class="grow">
            <v-layout align-center>
              <v-icon
                @click="like(post.id)"
                class="mr-1 like-icon"
              >{{post.liked ? 'favorite' : 'favorite_border'}}</v-icon>
              <span @click="openLikesDialog" class="subheading mr-2 like-span">{{post.likes_count}}</span>
              <v-icon @click="showCommentDialog" class="mr-1 comment-icon">add_comment</v-icon>
              <span class="subheading comment-icon">{{post.comments_count}}</span>
            </v-layout>
          </v-list-tile>
        </v-card-actions>
        <commentDialog
          :dialog="commentDialog"
          :post_id="post.id"
          @close-comment-dialog="closeCommentDialog"
        ></commentDialog>
        <likesDialog
          :dialog="likesDialog"
          :likes_loading="likes_loading"
          :likes="likes"
          :load_more="more_likes"
          @close-likes-dialog="closeLikesDialog"
          @load-more-likes="loadMoreLikes"
        ></likesDialog>
      </v-card>
    </v-flex>
  </v-flex>
</template>

<script>
// @ is an alias to /src

export default {
  name: "post",
  components: {
    commentDialog: () => import("@/components/dialogs/CommentDialog"),
    likesDialog: () => import("@/components/dialogs/showLikes")
  },
  data() {
    return {
      post: {
        id: 0,
        body: "",
        user_id: 0,
        owner_image: "",
        owner_name: "",
        image: null,
        liked: false,
        likes_count: 0,
        comments_count: 0
      },
      commentDialog: false,
      likesDialog: false,
      likes_loading: true,
      likes: [],
      likes_page: 1,
      more_likes: true
    };
  },
  created() {
    this.getPost();
  },
  methods: {
    getPost() {
      var id = this.$route.params.id;
      let token = this.$cookies.get("Utoken");
      this.$http
        .get(
          `http://localhost/binzo/backend/apis/post/getSinglePost.php?id=${id}`,
          {
            headers: {
              Authorization: "Bearer " + token
            }
          }
        )
        .then(response => {
          this.post = response.data.post;
        })
        .catch(error => {
          console.log(error);
        });
    },
    likeTrigger() {
      this.post.liked = !this.post.liked;
      this.post.liked == true
        ? this.post.likes_count++
        : this.post.likes_count--;
    },
    commentAdded(post_id) {
      this.post.comments_count++;
    },
    showCommentDialog() {
      this.commentDialog = true;
    },
    closeCommentDialog(value) {
      if (value.state == true) {
        this.post.comments_count++;
      }
      this.commentDialog = false;
    },
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
          this.likeTrigger();
        })
        .catch(error => {
          console.log(error);
        });
    },
    getLikes() {
      let token = this.$cookies.get("Utoken");
      this.$http
        .get(
          `http://localhost/binzo/backend/apis/like/getPostLikes.php?id=${
            this.post.id
          }&page=${this.likes_page}`,
          {
            headers: {
              Authorization: "Bearer " + token
            }
          }
        )
        .then(response => {
          let likes = this.likes;
          let new_likes = response.data.likes.data;
          if (new_likes.length == 0) {
            this.more_likes = false;
          }
          this.likes = likes.concat(new_likes);
        })
        .catch(error => {
          console.log(error);
        });
    },
    openLikesDialog() {
      this.getLikes();
      this.likesDialog = true;
    },
    closeLikesDialog() {
      this.likesDialog = false;
      this.likes.length = 0;
      this.likes_page = 1;
      this.more_likes = true;
    },
    loadMoreLikes() {
      this.likes_page++;
      this.getLikes();
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
  cursor: pointer;
}
.comment-icon {
  color: #ffa726 !important;
}
</style>