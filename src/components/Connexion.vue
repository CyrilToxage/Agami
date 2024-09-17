<template>
  <form class="login-form" @submit.prevent>
    <input type="text" v-model="userName" placeholder="Nom d'utilisateur" />
    <input type="password" v-model="userPassword" placeholder="Mot de passe" />
    <button @click="submitForm">Se connecter</button>
  </form>
</template>
<style>
.login-form {
  width: 300px;
  margin: 50px auto;
  padding: 20px;
  border-radius: 10px;
  background-color: #f8f9fa;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.login-form input[type="text"],
.login-form input[type="password"] {
  width: 100%;
  padding: 10px;
  margin: 10px 0;
  border: 1px solid #ced4da;
  border-radius: 5px;
  box-sizing: border-box;
}

.login-form button {
  width: 100%;
  padding: 10px;
  margin: 10px 0;
  border: none;
  border-radius: 5px;
  background-color: #007bff;
  color: #fff;
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.login-form button:hover {
  background-color: #0056b3;
}
</style>
<script>
export default {
  data() {
    return {
      userName: "",
      userPassword: "",
      loginCookie: {}
    };
  },
  mounted() {

    this.autoLogin();    

  },
  methods: {
    submitForm() {

      const formData = new FormData();
      formData.append("userName", this.userName);
      formData.append("userPassword", this.userPassword);

      this.login({
        method: "POST",
        body: formData
      }, true);

    },
    login(data, fromForm) {     

      fetch(`${import.meta.env.VITE_BACKEND_URL}/admin_connexion.php`, data)
        .then((response) => {
          if (!response.ok) {
            throw new Error("Network response was not ok");
          }
          return response.text();
        })
        .then((data) => {
          let res = JSON.parse(data) || data;

          if (res.status == "success") {
            this.$cookies.set('loginCookie', {
              userName: fromForm ? this.userName : this.loginCookie.userName,
              userPassword: fromForm ? this.userPassword : this.loginCookie.userPassword
            }, '1h');
            this.$store.commit("setLoggedIn", true);
            this.$store.commit("setUserInfo", res.data.user);
            this.$router.push("/admin");
          } else if (res.status == "fail") {
            this.showErrorMessage(res.data.message);
          } else {
            this.showErrorMessage("Erreur inconnue");
          }
        })
        .catch((error) => {
          console.error("Erreur lors de la soumission du formulaire:", error);
        });
    },
    autoLogin() {

      // Get login cookie
      this.loginCookie = this.$cookies.get('loginCookie') || {};
      
      // If login cookie exist
      if(this.loginCookie.userName && this.loginCookie.userPassword) {

        // Create formdata and login
        const formData = new FormData();
        formData.append("userName", this.loginCookie.userName);
        formData.append("userPassword", this.loginCookie.userPassword);

        this.login({
          method: "POST",
          body: formData
        }, false);

      }

    },
    showErrorMessage(message) {

      // A remplacer par un affichage plus propre
      alert(message);

    }
  }
};
</script>
