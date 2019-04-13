<template>
  <v-flex xs12>
    <v-flex ma-2>
      <postCard
        :id="post.id"
        :body="post.body"
        :user_id="post.user_id"
        :owner_image="post.owner_image"
        :owner_name="post.owner_name"
        :post_image="post.image"
        :liked="post.liked"
        :likes_count="post.likes_count"
        :comments_count="post.comments_count"
        @like-trigger="likeTrigger"
        @comment-added="commentAdded"
      ></postCard>
    </v-flex>
  </v-flex>
</template>

<script>
// @ is an alias to /src

export default {
  name: "post",
  components: {
    postCard: () => import("@/components/core/postCard")
  },
  data() {
    return {
      post: {
        id: 0,
        body: "",
        user_id: 0,
        owner_image: "",
        owner_name: "",
        post_image: null,
        liked: false,
        likes_count: 0,
        comments_count: 0
      }
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
          console.log(response);
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
    }
  }
};
</script>