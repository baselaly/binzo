<template>
<h1>contact</h1>
</template>

<script>
// // @ is an alias to /src
import axios from "axios";

export default {
  name: "contact",
  data() {
    return {};
  },
  components: {},
  created() {
    this.test();
  },
  methods: {
    test: function() {
      var config = {
        headers: {
          Authorization:
            "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1NDgyMzgxNzQsImlzcyI6ImxvY2FsaG9zdCIsInVzZXJJZCI6MzZ9.bVfd4VblOIVe9bBA_Zy-wXJQQiLP_9aXVlJaMhtbRq8"
        }
      };
      axios
        .get(
          "http://localhost/binzo/backend/apis/user/getHome.php?page=1",
          config
        )
        .then(response => {
          console.log(response);
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>