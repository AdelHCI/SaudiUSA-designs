<template>
  <div class="register">
    <v-row align="center" justify="center" fluid>
      <v-col cols="10" sm="10" md="8 " lg="6" xl="4">
        <v-card class="elevation-12">
          <v-toolbar color="primary" dark flat>
            <v-toolbar-title>تغيير كلمة السر</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-btn @click="$emit('registered', '')" icon large v-on="on">
                  <v-icon>mdi-backburger</v-icon>
                </v-btn>
              </template>
              <span>العودة لتسجيل الدخول</span>
            </v-tooltip>
          </v-toolbar>
          <v-card-text>
            <v-form v-model="valid">
              <v-text-field
                label="بريدك الالكتروني"
                :rules="emailErr"
                v-model="username"
              ></v-text-field>
              <v-text-field
                v-if="verify"
                type="password"
                label="كلمة السر الجديدة"
                :rules="pwdErr"
                v-model="pwd"
              ></v-text-field>
              <v-text-field
                v-if="verify"
                type="password"
                label="تأكيد كلمة السر الجديدة"
                :rules="cpwdErr"
                v-model="cpwd"
              ></v-text-field>
              <v-text-field
                v-if="verify"
                label="رمز التوثيق"
                :messages="codeMsg"
                :rules="codeErr"
                v-model="code"
              ></v-text-field>
              <v-btn color="primary" @click="reset" :disabled="!valid"
                >تغيير كلمة السر</v-btn
              >
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "Upload",
  props: {
    user_name: String,
  },
  data() {
    return {
      username: this.user_name ? this.user_name : "",
      pwd: "",
      cpwd: "",
      valid: false,
      emailErr: [
        (value) => !!value,
        (value) =>
          /[a-zA-Z]+.*[a-zA-Z]*@saudiusa.com/.test(value) ||
          "البريد الإلكتروني يجب يكون @saudiusa.com",
      ],
      pwdErr: [
        (value) => !!value,
        (value) =>
          /[a-z]+/.test(value) || "يجب أن تحتوي كلمة السر على حرف صغير",
        (value) =>
          /[A-Z]+/.test(value) || "يجب أن تحتوي كلمة السر على حرف كبير",
        (value) => /[0-9]+/.test(value) || "يجب أن تحتوي كلمة السر على رقم",
        (value) => /\W|_+/.test(value) || "يجب أن تحتوي كلمة السر على رمز",
        (value) => value.length >= 8 || "يجب أن تكون على الأقل 8 خانات",
      ],
      cpwdErr: [
        (value) => !!value,
        (value) => value == this.pwd || "يجب أن يكون التأكيد مطابق لكلمة السر",
      ],
      codeErr: [(value) => !!value],
      code: null,
      codeMsg: "تم إرسال رمز التوثيق على بريدك",
      verify: false,
      formData: new FormData(),
    };
  },
  methods: {
    reset() {
      this.formData.append("username", this.username);
      this.formData.append("code", this.code);
      const self = this;
      if (this.verify) {
        this.formData.append("pwd", this.pwd);
        this.formData.append("cpwd", this.cpwd);
      }
      axios
        .post("forgot.php", this.formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then(function(res) {
          console.log(res.data);
          if (res.data == 2) self.verify = true;
          else if (res.data == 1) self.$emit("reset", self.username);
          else if (res.data == 0) self.codeMsg = "رمز التوثيق غير صحيح";
          else if (res.data == -1) self.codeMsg = "لم تدخل كلمة سر";
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
.error {
  color: red;
}
.form-control {
  width: 80%;
  margin: auto;
  text-align: center;
  margin: 2px;
}
.row {
  max-width: 80%;
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
a {
  color: #42b983;
}
</style>
