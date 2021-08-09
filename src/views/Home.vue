<template>
  <div class="home">
    <v-btn
      color="primary"
      elevation="20"
      fixed
      dark
      style="z-index: 20"
      top
      left
      @click="$router.push('uploadfile')"
      >واجهة التحكم</v-btn
    >
    <v-row align="center" justify="center" fluid>
      <v-col cols="12">
        <v-card class="elevation-12" id="info">
          <v-toolbar color="primary" dark flat>
            <v-toolbar-title>بيانات التصميم</v-toolbar-title>
            <v-spacer></v-spacer>
          </v-toolbar>
          <v-card-text>
            <v-col cols="12">
              <v-text-field
                label="اسمك"
                id="name"
                type="text"
                v-model.lazy="name"
              ></v-text-field>
            </v-col>
            <v-row>
              <v-col cols="12" xs="12" s="6" md="6" lg="6" xl="6">
                <p>نوع النص</p>
                <v-overflow-btn
                  :items="fontTypes"
                  v-model="fontType"
                  label="نوع النص"
                ></v-overflow-btn>
              </v-col>
              <v-col cols="12" xs="12" s="6" md="6" lg="6" xl="6">
                <p>حجم النص</p>
                <v-overflow-btn
                  :items="fontSizes"
                  v-model="fontSize"
                  label="حجم النص"
                ></v-overflow-btn>
              </v-col>
            </v-row>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              v-if="(name != '') & (currImg != null)"
              color="primary"
              @click="refreshImg(false)"
              >استعراض التصميم</v-btn
            >
            <v-btn
              v-if="(name != '') & (currImg != null)"
              color="primary"
              @click="refreshImg(true)"
              >تنزيل التصميم</v-btn
            >
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
    <!-- <v-row align="center" justify="center">
      <v-col cols="10" xs="10" md="8" lg="4" xl="4">
        <v-card class="elevation-12">
          <v-img v-if="loading" :src="loadingSrc"></v-img>
          <v-img
            v-if="!loading & (src != null)"
            id="display"
            :src="src"
          ></v-img>
        </v-card>
        <v-card v-show="warn" class="elevation-12">
          <p id="warn" v-if="!loading & (src != null)">
            في حال تم تنزيل الملف بإسم document قم بإعادة التسمية إلى
            document.png
          </p>
        </v-card>
      </v-col>
    </v-row>

    <v-row align="center" justify="center" fluid>
      <v-col cols="12">
        <v-toolbar color="primary" dark flat>
          <v-spacer></v-spacer>
          <v-toolbar-title>
            <h1>اختر تصميم</h1>
          </v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>
        <v-row align="center" justify="center">
          <v-tabs show-arrows centered center-active>
            <v-tab @click="chooseType('الكل')">الكل</v-tab>
            <template v-for="t in types">
              <v-tab :key="t" @click="chooseType(t)">{{ t }}</v-tab>
            </template>
          </v-tabs>
        </v-row>
        <v-row align="center" justify="center">
          <template
            v-for="img in Object.values(imgs).filter(
              (k) => k.type == currType || currType == 'الكل'
            )"
          >
            <v-col :key="img.name" cols="12" xs="12" md="6" lg="4" xl="3">
              <v-card class="elevation-12">
                <v-img
                  :id="img.name"
                  :src="img.src.replace('uploads', 'thumbs')"
                ></v-img>
                <!-- <v-card-subtitle class="pb-0">{{
                  img.name.split(".")[0]
                }}</v-card-subtitle>
                <v-card-actions>
                  <v-btn color="green" text @click="chooseImg(img.name)"
                    >اختر</v-btn
                  >
                </v-card-actions>
              </v-card>
            </v-col>
          </template>
        </v-row>
      </v-col>
    </v-row> -->

    <Preview v-show="picked" :src="currImg.src" />
    <Designs
      :imgs="imgs"
      :types="types"
      currType="الكل"
      v-on:selected="selected"
    />
  </div>
</template>

<script>
import { createCanvas, loadImage } from "canvas";
import axios from "axios";
import Designs from "../components/Designs.vue";
import Preview from "../components/Preview.vue";
export default {
  name: "Home",
  components: {
    Designs,
    Preview,
  },
  data() {
    return {
      name: "",
      loading: false,
      loadingSrc: require("../assets/loading.gif"),
      imgs: [],
      types: [],
      download: null,
      currType: "الكل",
      canvas: null,
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
      },
      picked: false,
      fontType: "DriodKufi",
      fontSize: 30,
      fontTypes: ["DriodKufi", "DriodKufiBold"],
      fontSizes: [24, 30, 36, 40, 46, 52],
      warn:
        navigator.userAgent.includes("iPhone") ||
        navigator.userAgent.includes("Mac"),
    };
  },
  mounted() {
    axios.get("files.json").then((response) => {
      this.imgs = response.data;
    });
    axios.get("types.json").then((response) => {
      this.types = response.data;
    });
    new FontFace("DriodKufi", 'url("fonts/Kufi.ttf")').load().then((font) => {
      document.fonts.add(font);
    });
    new FontFace("DriodKufiBold", 'url("fonts/Kufi_Bold.ttf")')
      .load()
      .then((font) => {
        document.fonts.add(font);
      });
  },
  methods: {
    chooseType(type) {
      this.currType = type;
    },
    selected(res) {
      this.currImg = this.imgs[res.name];
      this.picked = true;
      if (res.op == "remove") {
        delete this.imgs[res.name];
        this.deleteImg();
      } else if (res.op == "edit") {
        axios.get(this.currImg.src).then((response) => {
          this.file = new File([response.data], this.currImg.name, {
            type: "image/png",
          });
        });
      }
    },
    // chooseImg(i) {
    //   document.getElementById("info").scrollIntoView();
    //   this.loading = true;
    //   this.currImg = this.imgs[i];
    //   this.src = this.currImg.src;
    //   this.loading = false;
    // },
    rgbToHex(rgb) {
      var hex = Number(rgb).toString(16);
      if (hex.length < 2) {
        hex = "0" + hex;
      }
      return hex;
    },
    async refreshImg(download) {
      if (!this.currImg) return;
      if (download & (this.canvas != null)) {
        this.downloadImg();
        return;
      }
      // this.loading = true;
      var img = await loadImage(this.currImg.src);
      img.width = img.width / 3;
      img.height = img.height / 3;
      this.canvas = createCanvas(img.width, img.height);
      const ctx = this.canvas.getContext("2d");
      const scale = img.width / this.currImg.width;
      ctx.drawImage(img, 0, 0, img.width, img.height);
      ctx.textAlign = "center";
      ctx.font = scale * this.fontSize + "px " + this.fontType;
      ctx.fillStyle =
        "#" +
        this.rgbToHex(this.currImg.r) +
        this.rgbToHex(this.currImg.g) +
        this.rgbToHex(this.currImg.b);
      ctx.fillText(
        this.name,
        (img.width * this.currImg.textX) / this.currImg.width,
        (img.height * this.currImg.textY) / this.currImg.height
      );
      this.currImg.src = this.canvas.toDataURL("image/png");
      this.loading = false;
      setTimeout(
        () => document.getElementById("display").scrollIntoView(),
        500
      );
      if (download) this.downloadImg();
    },
    downloadImg() {
      var link = document.createElement("a");
      link.setAttribute(
        "download",
        this.currImg.name.split(".")[0] + "-" + this.name + ".png"
      );
      link.href = this.currIm.src;
      link.target = "_blank";
      document.body.appendChild(link);
      link.click();
    },
  },
};
</script>

<style scoped>
#warn {
  font-size: 50%;
  color: red;
  height: fit-content;
}

img {
  display: block;
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
