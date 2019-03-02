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
          created_at: "2019-01-26 19:44:55",
          id: 27,
          body: "my second post",
          user_id: 26,
          fullname: "basel aly",
          image: null,
          user_image: "http://localhost/binzo/backend/uploads/users/user.png",
          likes_count: 1,
          liked: true,
          comments_count: 0
        },
        {
          created_at: "2019-01-26 19:44:34",
          id: 26,
          body: "my second post",
          user_id: 36,
          fullname: "baselbaselh alyaly",
          image: null,
          user_image:
            "http://localhost/binzo/backend/uploads/users/5c56047a087b8.jpg",
          likes_count: 0,
          liked: false,
          comments_count: 0
        },
        {
          created_at: "2019-01-23 17:19:44",
          id: 25,
          body: "my second post",
          user_id: 33,
          fullname: "basel aly",
          image: null,
          user_image:
            "http://localhost/binzo/backend/uploads/users/5c45cdae345ba.jpg",
          likes_count: 0,
          liked: false,
          comments_count: 0
        },
        {
          created_at: "2019-01-23 17:19:18",
          id: 24,
          body: "my second post",
          user_id: 36,
          fullname: "baselbaselh alyaly",
          image: null,
          user_image:
            "http://localhost/binzo/backend/uploads/users/5c56047a087b8.jpg",
          likes_count: 0,
          liked: false,
          comments_count: 0
        },
        {
          created_at: "2019-01-23 15:46:37",
          id: 22,
          body: "my second post",
          user_id: 36,
          fullname: "baselbaselh alyaly",
          image:
            "http://localhost/binzo/backend/uploads/posts/5c487e4d2c251.jpg",
          user_image:
            "http://localhost/binzo/backend/uploads/users/5c56047a087b8.jpg",
          likes_count: 0,
          liked: false,
          comments_count: 0
        },
        {
          created_at: "2019-01-23 15:16:47",
          id: 21,
          body: "my second post",
          user_id: 36,
          fullname: "baselbaselh alyaly",
          image:
            "http://localhost/binzo/backend/uploads/posts/5c48774f37485.jpg",
          user_image:
            "http://localhost/binzo/backend/uploads/users/5c56047a087b8.jpg",
          likes_count: 0,
          liked: false,
          comments_count: 0
        },
        {
          created_at: "2019-01-23 15:10:58",
          id: 20,
          body: "my second post",
          user_id: 36,
          fullname: "baselbaselh alyaly",
          image:
            "http://localhost/binzo/backend/uploads/posts/5c4875f2d8ec2.jpg",
          user_image:
            "http://localhost/binzo/backend/uploads/users/5c56047a087b8.jpg",
          likes_count: 0,
          liked: false,
          comments_count: 0
        },
        {
          created_at: "2019-01-23 15:09:44",
          id: 19,
          body: "my second post",
          user_id: 36,
          fullname: "baselbaselh alyaly",
          image: null,
          user_image:
            "http://localhost/binzo/backend/uploads/users/5c56047a087b8.jpg",
          likes_count: 0,
          liked: false,
          comments_count: 0
        },
        {
          created_at: "2019-01-23 14:23:19",
          id: 16,
          body: "my second post",
          user_id: 36,
          fullname: "baselbaselh alyaly",
          image: null,
          user_image:
            "http://localhost/binzo/backend/uploads/users/5c56047a087b8.jpg",
          likes_count: 0,
          liked: false,
          comments_count: 0
        },
        {
          created_at: "2019-01-23 14:14:33",
          id: 15,
          body: "my second post",
          user_id: 36,
          fullname: "baselbaselh alyaly",
          image: null,
          user_image:
            "http://localhost/binzo/backend/uploads/users/5c56047a087b8.jpg",
          likes_count: 0,
          liked: false,
          comments_count: 0
        },
        {
          created_at: "2019-01-23 14:10:00",
          id: 14,
          body: "my second post",
          user_id: 36,
          fullname: "baselbaselh alyaly",
          image: null,
          user_image:
            "http://localhost/binzo/backend/uploads/users/5c56047a087b8.jpg",
          likes_count: 0,
          liked: false,
          comments_count: 0
        },
        {
          created_at: "2019-01-23 13:42:01",
          id: 13,
          body: "my second post",
          user_id: 36,
          fullname: "baselbaselh alyaly",
          image: null,
          user_image:
            "http://localhost/binzo/backend/uploads/users/5c56047a087b8.jpg",
          likes_count: 0,
          liked: false,
          comments_count: 0
        },
        {
          created_at: "2019-01-23 13:18:07",
          id: 12,
          body: "my first post",
          user_id: 36,
          fullname: "baselbaselh alyaly",
          image: null,
          user_image:
            "http://localhost/binzo/backend/uploads/users/5c56047a087b8.jpg",
          likes_count: 0,
          liked: false,
          comments_count: 0
        },
        {
          created_at: "2019-01-23 13:07:41",
          id: 8,
          body: "my first post",
          user_id: 37,
          fullname: "basel aly",
          image: null,
          user_image: "http://localhost/binzo/backend/uploads/users/user.png",
          likes_count: 0,
          liked: false,
          comments_count: 0
        },
        {
          created_at: "2019-01-23 13:00:06",
          id: 7,
          body: "my first post",
          user_id: 28,
          fullname: "basel aly",
          image: null,
          user_image: "http://localhost/binzo/backend/uploads/users/user.png",
          likes_count: 0,
          liked: false,
          comments_count: 0
        },
        {
          created_at: "2019-01-23 12:41:37",
          id: 6,
          body: "my first post",
          user_id: 35,
          fullname: "basel aly",
          image: null,
          user_image:
            "http://localhost/binzo/backend/uploads/users/5c45ce61ebc6b.jpg",
          likes_count: 0,
          liked: false,
          comments_count: 0
        },
        {
          created_at: "2019-01-23 12:40:22",
          id: 5,
          body: "my first post",
          user_id: 36,
          fullname: "baselbaselh alyaly",
          image:
            "http://localhost/binzo/backend/uploads/posts/5c4852a61a08f.jpg",
          user_image:
            "http://localhost/binzo/backend/uploads/users/5c56047a087b8.jpg",
          likes_count: 0,
          liked: false,
          comments_count: 0
        },
        {
          created_at: "2019-01-23 12:39:23",
          id: 4,
          body: "my first post",
          user_id: 36,
          fullname: "baselbaselh alyaly",
          image:
            "http://localhost/binzo/backend/uploads/posts/5c48526bb4bcd.jpg",
          user_image:
            "http://localhost/binzo/backend/uploads/users/5c56047a087b8.jpg",
          likes_count: 0,
          liked: false,
          comments_count: 0
        },
        {
          created_at: "2019-01-23 12:16:01",
          id: 3,
          body: "my first post",
          user_id: 36,
          fullname: "baselbaselh alyaly",
          image: null,
          user_image:
            "http://localhost/binzo/backend/uploads/users/5c56047a087b8.jpg",
          likes_count: 0,
          liked: false,
          comments_count: 0
        },
        {
          created_at: "2019-01-23 12:05:06",
          id: 2,
          body: "my first post",
          user_id: 36,
          fullname: "baselbaselh alyaly",
          image: null,
          user_image:
            "http://localhost/binzo/backend/uploads/users/5c56047a087b8.jpg",
          likes_count: 0,
          liked: false,
          comments_count: 0
        }
      ]
    };
  }
};
</script>
