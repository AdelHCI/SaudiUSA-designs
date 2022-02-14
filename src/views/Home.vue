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
              @click="previewImg()"
              >استعراض التصميم</v-btn
            >
            <v-btn
              v-if="(name != '') & (currImg != null)"
              color="primary"
              @click="downloadImg()"
              >تنزيل التصميم</v-btn
            >
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>

    <Preview v-show="picked" :src="imgPreview" :ratio="ratio" />
    <Designs :imgs="imgs" :types="types" v-on:selected="selected" />
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
      ratio: 1,
      // loading: false,
      // loadingSrc: require("../assets/loading.gif"),
      imgs: [],
      types: [],
      download: null,
      canvas: null,
      imgPreview: null,
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
    selected(res) {
      this.currImg = this.imgs[res.name];
      this.imgPreview = this.currImg.src;
      this.ratio = this.currImg.width / this.currImg.height;
      this.picked = true;
      setTimeout(
        () => document.getElementById("display").scrollIntoView(),
        500
      );
    },
    rgbToHex(rgb) {
      var hex = Number(rgb).toString(16);
      if (hex.length < 2) {
        hex = "0" + hex;
      }
      return hex;
    },
    async previewImg() {
      if (!this.currImg) return;
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
        this.rgbToHex(this.currImg.b) +
        this.rgbToHex(parseFloat(this.currImg.a) * 255);
      ctx.fillText(
        this.name,
        (img.width * this.currImg.textX) / this.currImg.width,
        (img.height * this.currImg.textY) / this.currImg.height
      );
      this.imgPreview = this.canvas.toDataURL("image/png");
      // this.loading = false;
      setTimeout(
        () => document.getElementById("display").scrollIntoView(),
        500
      );
      // if (download) this.downloadImg();
      return this.imgPreview;
    },
    async downloadImg() {
      var link = document.createElement("a");
      link.setAttribute("download", this.name + ".png");
      link.href = await this.previewImg();
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
