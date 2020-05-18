<template>
  <div class="home">
    <v-row align="center" justify="center" fluid>
      <v-col cols="10" sm="10" md="8 " lg="6" xl="4">
        <v-card class="elevation-12" id="info">
          <v-toolbar color="primary" dark flat>
            <v-toolbar-title>بيانات التصميم</v-toolbar-title>
            <v-spacer></v-spacer>
          </v-toolbar>
          <v-card-text>
            <v-text-field label="اسمك" id="name" type="text" v-model.lazy="name"></v-text-field>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              v-if="name!='' & currImg!=null"
              color="primary"
              @click="refreshImg(false)"
            >استعراض التصميم</v-btn>
            <v-btn
              v-if="name!='' & currImg!=null"
              color="primary"
              @click="refreshImg(true)"
            >تنزيل التصميم</v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
    <v-row align="center" justify="center">
      <v-col cols="10" sm="10" md="8" lg="4" xl="4">
        <v-card class="elevation-12">
          <v-img v-if="loading" :src="loadingSrc"></v-img>
          <v-img v-if="!loading & src!=null" id="display" :src="src"></v-img>
        </v-card>
      </v-col>
    </v-row>

    <!-- <h1>اختر تصميم</h1> -->
    <v-row align="center" justify="center" fluid>
      <v-col cols="12" sm="12" md="10" lg="12" xl="12">
        <v-card class="elevation-12" id="info">
          <v-toolbar color="primary" dark flat>
            <v-spacer></v-spacer>
            <v-toolbar-title>
              <h1>اختر تصميم</h1>
            </v-toolbar-title>
            <v-spacer></v-spacer>
          </v-toolbar>
          <v-card-text>
            <v-row align="center" justify="center">
              <template v-for="(img,i) in imgs">
                <v-col :key="img.name" cols="10" sm="10" md="8" lg="4" xl="4">
                  <v-card class="elevation-12">
                    <v-img @click="chooseImg(i)" :id="img.name" :src="img.src"></v-img>
                    <v-card-subtitle class="pb-0">{{img.name.split(".")[0]}}</v-card-subtitle>
                    <v-card-actions>
                      <v-btn color="green" text @click="chooseImg(i)">اختر</v-btn>
                    </v-card-actions>
                  </v-card>
                </v-col>
              </template>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import { createCanvas, loadImage } from "canvas";
// import $ from "jquery";
import axios from "axios";
export default {
  name: "Home",
  data() {
    return {
      name: "",
      loading: false,
      loadingSrc: require("../assets/loading.gif"),
      imgs: null,
      download: null,
      canvas: null,
      currImg: null,
      src: null
    };
  },
  mounted() {
    axios.get("files.json").then(response => {
      this.imgs = response.data;
    });
    new FontFace("DriodKufi", 'url("fonts/Kufi.ttf")').load().then(font => {
      document.fonts.add(font);
    });
  },
  methods: {
    chooseImg(i) {
      document.getElementById("info").scrollIntoView();
      this.loading = true;
      this.currImg = this.imgs[i];
      this.src = this.currImg.src;
      this.loading = false;
    },
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
      this.loading = true;
      var img = await loadImage(this.currImg.src);
      img.width = img.width / 3;
      img.height = img.height / 3;
      this.canvas = createCanvas(img.width, img.height);
      const ctx = this.canvas.getContext("2d");
      const scale = img.width / this.currImg.width;
      ctx.drawImage(img, 0, 0, img.width, img.height);
      ctx.textAlign = "center";
      ctx.font = scale * 40 + "px DriodKufi";
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
      this.src = this.canvas.toDataURL("image/png");
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
        link.href = this.src.replace("base64","name="+this.currImg.name.split(".")[0] + "-" + this.name + ".png"+";base64");
        link.click();
        console.log(link.href)
      
      // this.canvas.toBlob(blob => {
      //   var link = document.createElement("a");
      // }, "image/png");

      //  = this.download;
      // link.target = "_blank";
      // document.body.appendChild(link);
    }
  }
};
</script>

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
a {
  color: #42b983;
}
</style>
