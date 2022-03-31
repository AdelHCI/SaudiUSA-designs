<template>
  <v-dialog v-model="dialog" max-width="80%">
    <v-card>
      <v-card-title class="justify-center headline">{{
        dialogMessage
      }}</v-card-title>
    </v-card>
  </v-dialog>
</template>