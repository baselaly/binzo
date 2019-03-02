<template>
  <v-flex xs12>
    <v-flex v-for="(post, i) in posts" :key="i" ma-2>
      <postCard
        :id="post.id"
        :body="post.body"
        :user_id="post.user_id"
        :created_at="post.created_at"
        :owner_image="post.owner_image"
        :owner_name="post.owner_name"
        :post_image="post.image"
        :liked="post.liked"
        :likes_count="post.likes_count"
        :comments_count="post.comments_count"
      ></postCard>
    </v-flex>
    <v-btn color="#ffa726" fab fixed top right>
      <v-icon>add</v-icon>
    </v-btn>
  </v-flex>
</template>

<script>
// @ is an alias to /src

export default {
  name: "home",
  components: {
    postCard: () => import("@/components/core/postCard")
  },
  data() {
    return {
      posts: [
        {
          id: 27,
          body: "my second post",
          user_id: 26,
          no_of_views: 0,
          created_at: "2019-01-26 7:44PM",
          owner_image: "http://localhost/binzo/backend/uploads/users/user.png",
          owner_name: "basel aly",
          image: null,
          likes_count: 1,
          liked: true,
          comments_count: 0
        },
        {
          id: 28,
          body: "my second post",
          user_id: 26,
          no_of_views: 0,
          created_at: "2019-01-26 7:44PM",
          owner_image: "http://localhost/binzo/backend/uploads/users/user.png",
          owner_name: "basel aly",
          image: null,
          likes_count: 1,
          liked: true,
          comments_count: 0
        },
        {
          id: 30,
          body: "my second post",
          user_id: 26,
          no_of_views: 0,
          created_at: "2019-01-26 7:44PM",
          owner_image: "http://localhost/binzo/backend/uploads/users/user.png",
          owner_name: "basel aly",
          image: null,
          likes_count: 1,
          liked: true,
          comments_count: 0
        },
        {
          id: 29,
          body: "my second post",
          user_id: 26,
          no_of_views: 0,
          created_at: "2019-01-26 7:44PM",
          owner_image: "http://localhost/binzo/backend/uploads/users/user.png",
          owner_name: "basel aly",
          image: null,
          likes_count: 1,
          liked: true,
          comments_count: 0
        },
        {
          id: 31,
          body: "my second post",
          user_id: 26,
          no_of_views: 0,
          created_at: "2019-01-26 7:44PM",
          owner_image: "http://localhost/binzo/backend/uploads/users/user.png",
          owner_name: "basel aly",
          image: null,
          likes_count: 1,
          liked: true,
          comments_count: 0
        }
      ]
    };
  }
};
</script>
