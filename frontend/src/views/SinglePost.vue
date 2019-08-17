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
        <v-flex two-line v-if="!comments_loading">
          <v-flex px-4 py-2 v-for="(comment,i) in comments" :key="i">
            <v-list-tile avatar ripple>
              <v-list-tile-avatar>
                <img :src="comment.user_image">
              </v-list-tile-avatar>
              <v-list-tile-content>
                <v-list-tile-title>{{comment.user_name}}</v-list-tile-title>
                <v-list-tile-sub-title class="pl-3 ma-1">{{ comment.comment }}</v-list-tile-sub-title>
              </v-list-tile-content>
              <v-list-tile-action>
                <v-list-tile-action-text>{{comment.created_at}}</v-list-tile-action-text>
              </v-list-tile-action>
            </v-list-tile>
            <v-divider v-if="i + 1 < comments.length"></v-divider>
          </v-flex>
          <v-flex text-xs-center>
            <v-btn
              v-if="more_comments"
              @click="getComments"
              round
              color="indigo"
              class="white--text"
            >More</v-btn>
          </v-flex>
        </v-flex>
        <v-flex v-else text-xs-center py-5>
          <v-progress-circular indeterminate :size="50" :width="5" color="white"></v-progress-circular>
        </v-flex>
        <commentDialog
          :dialog="commentDialog"
          :post_id="post.id"
          @close-comment-dialog="closeCommentDialog"
        ></commentDialog>
        <likesDialog
          :dialog="likesDialog"
          :loading="likes_loading"
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
      more_likes: false,
      // comments variables
      comments: [],
      more_comments: false,
      comments_page: 1,
      comments_loading: true
    };
  },
  created() {
    this.getPost();
  },
  methods: {
    getPost() {
      let id = this.$route.params.id;
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
          this.getComments();
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
        this.comments_loading = true;
        this.comments.length = 0;
        this.comments_page = 1;
        this.getComments();
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
          let likes_count = response.data.likes.total;
          this.likes = likes.concat(new_likes);
          this.likes.length < likes_count
            ? (this.more_likes = true)
            : (this.more_likes = false);
        })
        .catch(error => {
          console.log(error);
        });
    },
    openLikesDialog() {
      this.getLikes();
      this.likesDialog = true;
      setTimeout(() => {
        this.likes_loading = false;
      }, 500);
    },
    closeLikesDialog() {
      this.likesDialog = false;
      this.likes.length = 0;
      this.likes_page = 1;
      this.more_likes = false;
      setTimeout(() => {
        this.likes_loading = true;
      }, 500);
    },
    loadMoreLikes() {
      this.likes_page++;
      this.getLikes();
    },
    getComments() {
      if (!this.more_comments && this.comments_page !== 1) {
        return;
      }
      let post_id = this.post.id;
      let token = this.$cookies.get("Utoken");
      this.$http
        .get(
          `http://localhost/binzo/backend/apis/comment/getPostComments.php?id=${post_id}&page=${
            this.comments_page
          }`,
          {
            headers: {
              Authorization: "Bearer " + token
            }
          }
        )
        .then(response => {
          let comments = this.comments;
          let new_comments = response.data.comments.data;
          let comments_count = response.data.comments.total;
          this.comments = comments.concat(new_comments);
          this.comments.length < comments_count
            ? (this.more_comments = true)
            : (this.more_comments = false);
          this.comments_page++;
          setTimeout(() => {
            this.comments_loading = false;
          }, 1000);
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>
<style>
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