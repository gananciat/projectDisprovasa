<template>
  <div>
    <div>
      <v-app-bar
        color="#43a047"
        dark
        permanent
        src="https://picsum.photos/1920/1080?random"
        fade-on-scroll
      >
        <template v-slot:img="{ props }">
          <v-img
            v-bind="props"
            gradient="to top right, rgba(55,236,186,.7), rgba(25,32,72,.7)"
          ></v-img>
        </template>

        <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
        <v-toolbar-title>{{ sistema }}</v-toolbar-title>

        <v-spacer></v-spacer>

        <v-btn icon>
          <v-icon>mdi-dots-vertical</v-icon>
        </v-btn>
      </v-app-bar>
    </div>

    <div>
      <v-navigation-drawer
        absolute
        v-model="drawer"
        dark
        src="https://cdn.vuetifyjs.com/images/backgrounds/bg-2.jpg"        
        temporary
      >
        <v-list-item>
          <v-list-item-avatar>
            <v-img :src="logo"></v-img>
          </v-list-item-avatar>

          <v-list-item-content>
            <v-list-item-title>John Leider</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-divider></v-divider>

        <v-list>
          <v-list-item
            v-for="item in items"
            :key="item.title"
            link
          >
            <v-list-item-icon>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title @click="redigir('departament')">{{ item.title }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>

        <template v-slot:append>
          <div class="pa-2">
            <v-btn block>Logout</v-btn>
          </div>
        </template>        
      </v-navigation-drawer>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      sistema: String,
      logo: String
    },
    data: () => ({
      drawer: false,
      items: [
        { title: 'Home', icon: 'mdi-contact-mail' },
        { title: 'About', icon: 'mdi-account-alert-outline' },
      ],        
    }),

    methods: {
      redigir(ruta)
      {
        window.location.href = window.location.origin + '/' + ruta
      }
    },
  }
</script>