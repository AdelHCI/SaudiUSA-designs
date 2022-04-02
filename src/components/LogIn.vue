<template>
  <div class="login">
    <register
      v-on:registered="registered"
      v-if="register"
      :user_name="username"
    />
    <forgot v-on:reset="reset" v-else-if="forgot" :user_name="username" />
    <v-row v-else align="center" justify="center" fluid>
      <v-col cols="10" sm="10" md="8 " lg="6" xl="4">
        <v-card class="elevation-12">
          <v-toolbar color="primary" dark flat>
            <v-toolbar-title>تسجيل الدخول</v-toolbar-title>
            <v-spacer></v-spacer>
          </v-toolbar>
          <v-card-text>
            <v-form v-model="valid">
              <v-text-field
                :prepend-icon="mdiEmail"
                label="بريدك الالكتروني"
                type="text"
                :rules="emailErr"
                v-model="username"
              ></v-text-field>
              <v-text-field
                :prepend-icon="mdiLock"
                type="password"
                :rules="pwdErr"
                label="كلمة السر"
                v-model="pwd"
              ></v-text-field>
              <v-btn color="success" class="mr-4" @click="logIn"
                >تسجيل الدخول</v-btn
              >
              <v-btn color="primary" class="mr-4" @click="forgot = !forgot"
                >نسيت كلمة السر</v-btn
              >
              <v-btn color="primary" class="mr-4" @click="register = !register"
                >إنشاء حساب</v-btn
              >
              <v-text-field
                v-if="verify"
                label="رمز التوثيق"
                :messages="codeMsg"
                :rules="codeErr"
                v-model="code"
              ></v-text-field>
              <v-btn color="primary" v-if="verify" @click="verifyCode"
                >وثق الحساب</v-btn
              >
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import { mdiEmail, mdiLock } from "@mdi/js";
import axios from "axios";
import Register from "../components/Register.vue";
import Forgot from "../components/Forgot.vue";
export default {
  name: "LogIn",
  components: {
    Register,
    Forgot,
  },
  data() {
    return {
      username: "",
      valid: false,
      mdiEmail: mdiEmail,
      mdiLock: mdiLock,
      pwd: "",
      register: false,
      forgot: false,
      pwdMsg: "",
      emailErr: [
        (value) => !!value,
        (value) =>
          /[a-zA-Z]+.*[a-zA-Z]*@saudiusa.com/.test(value) ||
          "البريد الإلكتروني يجب أن يكون @saudiusa.com",
      ],
      pwdErr: [
        (value) => !!value,
        (value) =>
          /[a-z]+/.test(value) || "يجب أن تحتوي كلمة السر على حرف صغير",
        (value) =>
          /[A-Z]+/.test(value) || "يجب أن تحتوي كلمة السر على حرف كبير",
        (value) => /[0-9]+/.test(value) || "يجب أن تحتوي كلمة السر على رقم",
        (value) => /\W|_+/.test(value) || "يجب أن تحتوي كلمة السر على رمز",
        (value) =>
          value.length >= 8 || "يجب أن تكون كلمة السر على الأقل 8 خانات",
      ],
      codeErr: [(value) => !!value],
      code: null,
      codeMsg: "تم إرسال رمز التوثيق على بريدك",
      verify: false,
      formData: new FormData(),
    };
  },
  methods: {
    registered(res) {
      this.username = res.username;
      this.pwdMsg = res.forgotMsg;
      this.register = false;
    },
    reset(username) {
      this.username = username;
      this.forgot = false;
      this.pwdMsg = "قم بإدخال كلمة السر الجديدة";
    },
    logIn() {
      if (!this.valid) {
        document
          .querySelector(".v-messages.error--text:first-of-type")
          .scrollIntoView();
        return;
      }
      this.formData.append("username", this.username);
      this.formData.append("pwd", this.pwd);
      axios
        .post("login.php", this.formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          if (res.data == -1) this.pwdMsg = "كلمة السر غير صحيحة";
          else if (res.data == 0) this.pwdMsg = "بريدك الالكتروني غير صحيح";
          else if (res.data == 2) this.verify = true;
          else this.$emit("loggedIn", [true, res.data]);
        })
        .catch((res) => {
          console.log(res);
        });
    },
    verifyCode() {
      this.formData.append("username", this.username);
      this.formData.append("code", this.code);
      axios
        .post("verify.php", this.formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          console.log(res);
          this.verify = res.data != 1;
          this.logIn();
        })
        .catch(function(res) {
          console.log("FAILURE!! " + res);
          console.log(res);
        });
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.form-group {
  width: 80%;
  margin: auto;
  padding: 15px;
  margin-top: 2rem;
  display: flex;
  flex-flow: column;
  align-items: center;
  padding-bottom: 2rem;
}

.form-control {
  width: 80%;
  margin: auto;
  text-align: center;
  margin: 2px;
}
img {
  display: block;
  margin: auto;
}
.row {
  /* max-width: 80%; */
  margin: auto;
}
.card {
  margin: auto;
}
h3 {
  margin: 40px 0 0;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
</style>
