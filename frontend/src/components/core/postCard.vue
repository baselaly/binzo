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
      </v-list-tile>
    </v-card-title>
    <router-link to="/login" class="white--text post-link">
      <v-card-text class="subheading font-weight-light">
        {{body}}
        <v-img v-if="post_image!=null" class="my-4" :src="post_image"></v-img>
      </v-card-text>
    </router-link>

    <v-card-actions>
      <v-list-tile class="grow">
        <v-layout align-center>
          <v-icon @click="like(id)" class="mr-1">{{liked ? 'favorite' : 'favorite_border'}}</v-icon>
          <span class="subheading mr-2">{{likes_count}}</span>
          <v-icon class="mr-1">mode_comment</v-icon>
          <span class="subheading">{{comments_count}}</span>
        </v-layout>
      </v-list-tile>
    </v-card-actions>
  </v-card>
</template>

<script>
// @ is an alias to /src

export default {
  name: "postCard",
  components: {},
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
    }
  },
  methods: {
    like(post_id) {
      this.liked = !this.liked;
    }
  }
};
</script>
<style>
.post-link {
  text-decoration: none;
}
</style>
