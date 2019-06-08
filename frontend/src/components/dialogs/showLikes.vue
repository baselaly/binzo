<template>
  <v-dialog v-model="dialog" width="250" persistent>
    <v-card v-if="loading" style="padding:50px; text-align:center;">
      <v-progress-circular indeterminate :size="50" :width="5" color="white"></v-progress-circular>
    </v-card>
    <v-card class="text-xs-center pb-2" v-if="!loading">
      <v-card-title>
        <span class="title">Likes</span>
        <v-spacer></v-spacer>
        <v-btn flat icon @click="closeLikesDialog">
          <v-icon>close</v-icon>
        </v-btn>
      </v-card-title>
      <v-flex text-xs-center v-for="(like, i) in likes" :key="i">
        <v-list-tile avatar>
          <v-list-tile-avatar>
            <img :src="like.user_image">
          </v-list-tile-avatar>
          <v-list-tile-content>
            <v-list-tile-title>{{like.user_name}}</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-flex>
      <v-flex text-xs-center>
        <v-btn v-if="load_more" @click="loadMore" round color="indigo" class="white--text">More</v-btn>
      </v-flex>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  data() {
    return {};
  },
  props: {
    dialog: {
      required: true,
      type: Boolean
    },
    likes: {
      required: true,
      type: Array
    },
    loading: {
      required: true,
      type: Boolean
    },
    load_more: {
      required: true,
      type: Boolean
    }
  },
  methods: {
    closeLikesDialog() {
      this.$emit("close-likes-dialog");
    },
    loadMore() {
      this.$emit("load-more-likes");
    }
  }
};
</script>