<template>
  <div class="upload">
    <v-btn
      v-show="loggedIn"
      color="error"
      elevation="20"
      fixed
      dark
      style="z-index: 20"
      top
      left
      @click="logout"
    >
      تسجيل خروج
    </v-btn>
    <v-btn
      v-show="!loggedIn"
      color="primary"
      elevation="20"
      fixed
      dark
      style="z-index: 20"
      top
      left
      @click="$router.push('/')"
    >
      واجهة التصاميم
    </v-btn>
    <LogIn v-on:loggedIn="login" v-if="!loggedIn" />
    <v-col v-else align="center" justify="center" fluid>
      <v-row>
        <v-tabs v-model="tab" show-arrows centered center-active>
          <v-tab @click="clear">إضافة تصميم</v-tab>
          <template v-if="admin">
            <v-tab>تعديل تصميم</v-tab>
            <v-tab>حذف تصميم</v-tab>
            <v-tab>الصلاحيات</v-tab>
          </template>
        </v-tabs>
      </v-row>
      <v-tabs-items v-model="tab" touchless>
        <v-tab-item>
          <UploadForm
            :upload="true"
            :currImg="currImg"
            :types="types"
            :jsonfiles="jsonfiles"
            v-on:dialogMsg="dialogMsg"
          />
        </v-tab-item>
        <v-tab-item>
          <UploadForm
            :currImg="currImg"
            :upload="false"
            v-show="picked"
            :file="file"
            :types="types"
            :jsonfiles="jsonfiles"
            :picked="picked"
            v-on:dialogMsg="dialogMsg"
            v-on:unpick="clear"
          />
          <Preview v-show="picked" :src="currImg.src" />
          <Designs
            :edit="true"
            v-show="!picked"
            :imgs="jsonfiles"
            :types="types"
            :currType="currType"
            v-on:selected="selected"
          />
        </v-tab-item>
        <v-tab-item>
          <Designs
            :remove="true"
            :imgs="jsonfiles"
            :types="types"
            :currType="currType"
            v-on:selected="selected"
          />
        </v-tab-item>
        <v-tab-item>
          <Users :currUser="username" :admin="userrole" v-on:check="check" />
        </v-tab-item>
      </v-tabs-items>
    </v-col>
    <v-dialog v-model="dialog" max-width="290">
      <v-card>
        <v-card-title class="justify-center headline">
          {{ dialogMessage }}
        </v-card-title>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import axios from "axios";
import LogIn from "../components/LogIn.vue";
import Designs from "../components/Designs.vue";
import UploadForm from "../components/UploadForm.vue";
import Preview from "../components/Preview.vue";
import Users from "../components/Users.vue";
export default {
  name: "Upload",
  components: {
    LogIn,
    Designs,
    Users,
    UploadForm,
    Preview,
  },
  data() {
    return {
      tab: null,
      admin: null,
      dialogMessage: "",
      dialog: false,
      loggedIn: false,
      currImg: {
        name: "",
        src: null,
        width: null,
        height: null,
        type: null,
        textX: null,
        textY: null,
        r: null,
        g: null,
        b: null,
        a: 1,
      },
      jsonfiles: [],
      types: [],
      file: null,
      username: null,
      userrole: null,
      currType: "الكل",
    };
  },
  mounted() {
    this.check();
    axios.get("files.json").then((response) => {
      this.jsonfiles = response.data;
    });
    axios.get("types.json").then((response) => {
      this.types = response.data;
    });
  },
  methods: {
    logout() {
      axios
        .post("logout.php")
        .then((res) => {
          console.log(res);
          this.dialogMessage = "تم تسجيل الخروج";
          this.dialog = true;
          this.check();
        })
        .catch((res) => {
          console.log(res);
        });
    },
    clear() {
      this.currType = "الكل";
      this.picked = false;
      this.currImg = {
        name: "",
        src: null,
        width: null,
        height: null,
        type: null,
        textX: null,
        textY: null,
        r: null,
        g: null,
        b: null,
        a: null,
      };

      this.file = null;
    },
    dialogMsg(res) {
      this.clear();
      this.dialogMessage = res.msg;
      this.dialog = true;
      // console.log(res);
      if (res.jsonfiles) this.jsonfiles = res.jsonfiles;
      if (res.types) this.types = res.types;
      this.currType = "الكل";
    },
    selected(res) {
      if (res.op == "remove") {
        delete this.jsonfiles[res.name];
        this.deleteImg();
      } else if (res.op == "edit") {
        this.currImg = this.jsonfiles[res.name];
        axios.get(this.currImg.src).then((response) => {
          this.file = new File([response.data], this.currImg.name, {
            type: "image/png",
          });
        });
        this.picked = true;
      }
    },
    login(res) {
      this.loggedIn = res[0];
      this.username = res[1][1];
      this.userrole = res[1][0];
      this.admin = this.userrole == "مشرف";
    },
    check() {
      axios
        .post("check.php", this.formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          this.loggedIn = parseInt(res.data) != -1;
          this.username = res.data[1];
          this.userrole = res.data[0];
          this.admin = this.userrole == "مشرف";
        })
        .catch((res) => {
          console.log(res);
        });
    },
    deleteImg() {
      this.types = this.types.filter(
        (k) =>
          Object.values(this.jsonfiles).filter((f) => f.type == k).length > 0
      );
      this.formData = new FormData();
      this.formData.append("json", JSON.stringify(this.jsonfiles));
      this.formData.append("types", JSON.stringify(this.types));
      let self = this;
      axios
        .post("upload.php", this.formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then(function(res) {
          self.dialog = true;
          self.dialogMessage = "تم حذف التصميم";
          self.currType = "الكل";
          console.log(res);
        })
        .catch(function(res) {
          self.dialog = true;
          self.dialogMessage = "فشلت عملية الحذف";
          console.log(res);
        });
    },
    // uploadImg() {
    //   if (!this.valid) {
    //     document
    //       .querySelector(".v-messages.error--text:first-of-type")
    //       .scrollIntoView();
    //     return;
    //   }
    //   this.loading = true;
    //   this.formData.append("file", this.file);
    //   this.currImg.name = this.file.name;
    //   this.currImg.src = "./uploads/" + this.file.name;
    //   this.jsonfiles[this.currImg.name] = this.currImg;
    //   if (!this.types.find((t) => t != this.type)) {
    //     this.types.push(this.type);
    //   }

    //   this.formData.append("json", JSON.stringify(this.jsonfiles));
    //   this.formData.append("types", JSON.stringify(this.types));
    //   let self = this;
    //   axios
    //     .post("upload.php", this.formData, {
    //       headers: {
    //         "Content-Type": "multipart/form-data",
    //       },
    //     })
    //     .then(function (res) {
    //       self.loading = false;
    //       self.dialog = true;
    //       self.dialogMessage = "تم رفع/تعديل الملف";
    //       console.log(res);
    //     })
    //     .catch(function (res) {
    //       self.fail = true;
    //       self.dialog = true;
    //       self.dialogMessage = "فشلت عملية الرفع/التعديل";
    //       console.log(res);
    //     });
    // },
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
