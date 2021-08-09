<template>
  <v-row align="center" justify="center" fluid>
    <template v-for="user in users">
      <v-col :key="user['USERNAME']" cols="12" xs="12" md="6" lg="4" xl="3">
        <v-card class="elevation-12">
          <v-card-title>
            {{ user["USERNAME"] }}
            <v-spacer></v-spacer>
            <v-chip color="primary">{{ user["USER_ROLE"] }}</v-chip>
          </v-card-title>
          <v-card-actions v-if="currUser != user['USERNAME']">
            <v-btn
              v-if="user['USER_ROLE'] != 'مشرف'"
              color="green"
              text
              @click="changeRole(user['USERNAME'], 'مشرف')"
            >
              تحويل لمشرف
            </v-btn>
            <v-btn
              v-else
              color="green"
              text
              @click="changeRole(user['USERNAME'], 'محرر')"
            >
              تحويل لمحرر
            </v-btn>
            <v-btn color="red" text @click="deleteUser(user['USERNAME'])">
              حذف المستخدم
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </template>
  </v-row>
</template>

<script>
import axios from "axios";
export default {
  name: "Users",
  props: { admin: Boolean, currUser: Text },
  data() {
    return {
      users: null,
      formData: new FormData(),
    };
  },
  mounted() {
    axios.get("getUsers.php").then((response) => {
      this.users = response.data;
    });
  },
  methods: {
    changeRole(name, role) {
      this.formData.append("name", name);
      this.formData.append("role", role);
      let self = this;
      axios
        .post("updateRole.php", this.formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then(function (res) {
          console.log(res);
          axios.get("getUsers.php").then((response) => {
            self.users = response.data;
            self.$emit("check");
          });
        })
        .catch(function (res) {
          console.log(res);
        });
    },
    deleteUser(name) {
      this.formData.append("name", name);
      let self = this;
      axios
        .post("deleteUser.php", this.formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then(function (res) {
          console.log(res);
          axios.get("getUsers.php").then((response) => {
            self.users = response.data;
          });
        })
        .catch(function (res) {
          console.log(res);
        });
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
img {
  display: block;
  margin: auto;
}
.card {
  margin: auto;
}
</style>
