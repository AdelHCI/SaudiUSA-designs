<template>
  <v-row>
    <v-col align="center" justify="center" cols="12">
      <v-card class="elevation-12">
        <v-toolbar color="primary" dark flat>
          <v-toolbar-title>بيانات الملف</v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>
        <v-card-text>
          <v-form v-model="valid"
            ><v-combobox
              :rules="notEmpty"
              v-model="currImg.type"
              :items="types"
              label="تصنيف"
            ></v-combobox>
            <v-text-field
              :rules="notEmpty"
              label="طول الصورة"
              type="text"
              v-model="currImg.height"
            ></v-text-field>
            <v-text-field
              :rules="notEmpty"
              label="عرض الصورة"
              type="text"
              v-model="currImg.width"
            ></v-text-field>
            <v-text-field
              :rules="notEmpty"
              label="موقع النص بالعرض"
              type="text"
              v-model="currImg.textX"
            ></v-text-field>
            <v-text-field
              :rules="notEmpty"
              label="موقع النص بالطول"
              type="text"
              v-model="currImg.textY"
            ></v-text-field>
            <v-text-field
              :rules="RGBrules"
              label="قيمة اللون الأحمر"
              type="text"
              v-model="currImg.r"
            ></v-text-field>
            <v-text-field
              :rules="RGBrules"
              label="قيمة اللون الأخضر"
              type="text"
              v-model="currImg.g"
            ></v-text-field>
            <v-text-field
              :rules="RGBrules"
              label="قيمة اللون الأزرق"
              type="text"
              v-model="currImg.b"
            ></v-text-field>
            <v-file-input
              :rules="filesRules"
              accept="image/jpeg, image/png"
              chips
              show-size
              :label="fileLabel"
              v-model="file"
            ></v-file-input>
            <br />
            <v-card>
              <v-progress-linear
                :value="progress"
                height="20"
                rounded
                v-show="loading"
              >
                <strong>جاري {{ mode }} التصميم ({{ progress }}%)</strong>
              </v-progress-linear>
            </v-card>
            <br />
            <v-btn color="success" class="mr-4" @click="uploadImg">
              {{ mode }} التصميم
            </v-btn>
            <v-btn
              color="success"
              v-show="picked"
              class="mr-4"
              @click="$emit('unpick')"
            >
              أختار تصميم آخر
            </v-btn>
          </v-form>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
import axios from "axios";
export default {
  name: "UploadForm",
  props: {
    currImg: Object,
    types: Object,
    upload: Boolean,
    picked: Boolean,
    jsonfiles: Object,
    file: Object,
  },
  data() {
    return {
      mode: this.upload ? "تحميل" : "تعديل",
      valid: false,
      filesRules: [(value) => !!value || "يجب تحديد ملف التصميم"],
      notEmpty: [(value) => !!value || value == "" || "يجب وضع قيمة"],
      RGBrules: [
        (value) =>
          !!value ||
          parseInt(value) > 255 ||
          parseInt(value) < 0 ||
          "قيم اللون بين 0 و 255",
      ],
      fileLabel: "اختر ملف",
      formData: new FormData(),
      loading: false,
      progress: 0,
    };
  },
  methods: {
    uploadImg() {
      if (!this.valid) {
        document
          .querySelector(".v-messages.error--text:first-of-type")
          .scrollIntoView();
        return;
      }
      this.loading = true;
      this.formData.append("file", this.file);
      this.currImg.name = this.file.name;
      this.currImg.src = "./uploads/" + this.file.name;
      this.jsonfiles[this.currImg.name] = this.currImg;
      this.types = this.types.filter(
        (k) =>
          Object.values(this.jsonfiles).filter((f) => f.type == k).length > 0
      );
      // if (this.types.findIndex((t) => t == this.currImg.type) == -1) {
      console.log(this.types);
      this.types.push(this.currImg.type);
      console.log(this.types);
      this.types = [...new Set(this.types)];
      console.log(this.types);
      // } else {
      //   console.log(this.currImg.type);
      // }
      this.formData.append("json", JSON.stringify(this.jsonfiles));
      this.formData.append("types", JSON.stringify(this.types));
      let self = this;
      axios
        .post("upload.php", this.formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
          onUploadProgress: (progressEvent) => {
            const totalLength = progressEvent.lengthComputable
              ? progressEvent.total
              : progressEvent.target.getResponseHeader("content-length") ||
                progressEvent.target.getResponseHeader(
                  "x-decompressed-content-length"
                );
            if (totalLength !== null) {
              self.progress = Math.round(
                (progressEvent.loaded * 100) / totalLength
              );
            }
          },
        })
        .then(function(res) {
          self.progress = 100;
          self.loading = false;
          self.$emit("dialogMsg", {
            msg: "تم " + self.mode + " الملف",
            jsonfiles: self.jsonfiles,
            types: self.types,
          });
          console.log(res);
        })
        .catch(function(res) {
          self.loading = false;
          self.$emit("dialogMsg", "فشلت عملية ال" + self.mode);
          console.log(res);
        });
    },
  },
};
</script>

<style scoped>
img {
  display: block;
  margin: auto;
}
.card {
  margin: auto;
}
</style>
