<template>
    <v-app>
        <v-navigation-drawer app class="teal" dark permanent>
            <!--BEGIN SIDEBAR -->
            <v-list>
                <v-list-item link :to="'/producto'">
                    <v-list-item-icon>
                        <v-icon>mdi-apps</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Productos</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link :to="'/venta/nuevo'">
                    <v-list-item-icon>
                        <v-icon>mdi-store</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Nueva venta</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
            <!-- END SIDE BAR -->
        </v-navigation-drawer>

        <v-app-bar app color="teal darken-4" dense dark>
            <!-- BEGIN NAVBAR -->
            <v-toolbar-title>EIGHT POS</v-toolbar-title>
            <!-- END NAVBAR -->
        </v-app-bar>

        <!-- Sizes your content based upon application components -->
        <v-main>
            <!-- Provides the application the proper gutter -->
            <v-container fluid>
                <!-- If using vue-router -->
                <router-view></router-view>
            </v-container>
        </v-main>

        <v-footer app color="teal darken-4" dark padless>
            <v-card-text class="py-2 white--text text-center">
                {{ new Date().getFullYear() }} — <strong>Eight</strong>
            </v-card-text>
        </v-footer>
    </v-app>
</template>
<script>
export default {
    data() {
        return {
            items: [
                { title: "Dashboard", icon: "mdi-view-dashboard" },
                { title: "Account", icon: "mdi-account-box" },
                { title: "Admin", icon: "mdi-gavel" }
            ]
        };
    }
};
</script>
