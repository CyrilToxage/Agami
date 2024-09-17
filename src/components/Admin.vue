<template>
  <!-- Template section -->
  <div class="d-flex flex-column h-100">
    <header>
      <!-- Header section -->
      <!-- Fixed navbar -->
      <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark"
        style="background: linear-gradient(90deg, #808080 10%, #0077ff 80%)">
        <div class="container-fluid">
          <a class="navbar-brand text-dark" href="/">Agami-Light</a>
          <button class="navbar-toggler bg-transparent" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
              <li class="nav-item">
                <button class="btn btn-primary btn-sm custom-bg-orange" @click="logout()">
                  <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </header>

    <!-- Main content section -->
    <!-- Begin page content -->
    <main class="container py-5">
      <h2 id="clients" class="text-center mb-4">Demandes de contact</h2>
      <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-lg-12">
            <div class="container text-center">
              <table class="table">
                <tbody>
                  <tr v-for="client in clients" :key="client.cliID">
                    <td class="border border-4 align-items-center">
                      <!--Reçu a-->
                      <div class="row">
                        <div class="col border p-2">
                          <h5>Reçu à</h5>
                          <input type="datetime-local" v-model="client.cliCreationDate" disabled/>
                        </div>
                        <!--Nom-->
                        <div class="col border p-2">
                          <h5>Nom</h5>
                          <input type="text" v-model="client.cliNom" disabled/>
                        </div>
                        <!--Prénom-->
                        <div class="col border p-2">
                          <h5>Prénom</h5>
                          <input type="text" v-model="client.cliPrenom" disabled/>
                        </div>
                        <!--Type-->
                        <div class="col border p-2">
                          <h5>Type</h5>
                          <select v-model="client.cliTypID" disabled>
                            <option value="1">Événementiel</option>
                            <option value="2">Résidentiel</option>
                            <option value="3">Tertiaire</option>
                            <option value="4">Industriel</option>
                          </select>
                        </div>
                        <!--Statut-->
                        <div class="col border p-2">
                          <h5>Statut</h5>
                          <select v-if="!client.editMode" v-model="client.cliValide" disabled>
                            <option value="0">Prospect</option>
                            <option value="1">Client</option>
                          </select>
                        </div>
                        <!-- Bouton Fiche Client -->
                        <div class="col border p-2">
                          <h5>Action</h5>
                          <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            @click="openPopup({...client})">
                            Fiche Client
                          </button>
                        </div>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div v-if="clientDansPopup" class="modal show" tabindex="-1" role="dialog"
        style="display: block; background-color: rgba(0, 0, 0, 0.5)">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Fiche Client</h5>
              <button type="button" class="btn-close" aria-label="Close" @click="closePopup()"></button>
            </div>
            <div class="modal-body">
              <div class="container">
                <table class="table">
                  <tbody>
                    <tr>
                      <td class="border border-4 align-items-center">
                        <div class="mb-3">
                          <h5>Reçu à</h5>
                          <input type="datetime-local" class="form-control" :value="clientDansPopup.cliCreationDate" disabled/>
                        </div>
                        <div v-if="clientDansPopup.cliDatesTimes" class="mb-3">
                          <h5>Créneaux d'intervention</h5>
                          <div class="date-time-entry mb-3" v-for="dateTime in JSON.parse(clientDansPopup.cliDatesTimes)" :key="dateTime.date">
                            <div class="d-flex align-items-center gap-2">
                              <input type="date" class="form-control" :value="dateTime.date.split('T')[0]" disabled/>
                              <input type="time" class="form-control" :value="dateTime.startTime" disabled/>
                              <input type="time" class="form-control" :value="dateTime.endTime" disabled/>
                            </div>
                          </div>
                        </div>
                        <div class="mb-3">
                          <h5>Nom</h5>
                          <input type="text" class="form-control" v-model="clientDansPopup.cliNom" :disabled="!clientDansPopup.editMode"/>
                        </div>
                        <div class="mb-3">
                          <h5>Prénom</h5>
                          <input type="text" class="form-control" v-model="clientDansPopup.cliPrenom" :disabled="!clientDansPopup.editMode"/>
                        </div>
                        <div class="mb-3">
                          <h5>Sexe</h5>
                          <select class="form-select" v-model="clientDansPopup.cliSexe" :disabled="!clientDansPopup.editMode">
                            <option value="Homme">Homme</option>
                            <option value="Femme">Femme</option>
                            <option value="Autres">Autres</option>
                          </select>
                        </div>
                        <div class="mb-3">
                          <h5>Email</h5>
                          <input type="email" class="form-control" v-model="clientDansPopup.cliMail" :disabled="!clientDansPopup.editMode"/>
                        </div>
                        <div class="mb-3">
                          <h5>Numéro de téléphone</h5>
                          <input type="tel" class="form-control" v-model="clientDansPopup.cliNumero" :disabled="!clientDansPopup.editMode"/>
                        </div>
                        <div class="mb-3">
                          <h5>Rue</h5>
                          <input type="text" class="form-control" v-model="clientDansPopup.cliRue" :disabled="!clientDansPopup.editMode"/>
                        </div>
                        <div class="mb-3">
                          <h5>Ville</h5>
                          <input type="text" class="form-control" v-model="clientDansPopup.cliVille" :disabled="!clientDansPopup.editMode"/>
                        </div>
                        <div class="mb-3">
                          <h5>Type</h5>
                          <select class="form-select" v-model="clientDansPopup.cliTypID" :disabled="!clientDansPopup.editMode">
                            <option value="1">Événementiel</option>
                            <option value="2">Résidentiel</option>
                            <option value="3">Tertiaire</option>
                            <option value="4">Industriel</option>
                          </select>
                        </div>
                        <div class="mb-3" v-if="['2', '3', '4'].includes(clientDansPopup.cliTypID)">
                          <h5>Sous-type</h5>
                          <select class="form-select" v-model="clientDansPopup.cliSouID" :disabled="!clientDansPopup.editMode">
                            <option value="1">Nouvelle installation</option>
                            <option value="2">Remise en conformité</option>
                            <option value="3">Rénovation</option>
                            <option value="4">Dépannage</option>
                            <option v-if="clientDansPopup.cliTypID != '2'" value="5">
                              Service de maintenance
                            </option>
                          </select>
                        </div>
                        <div class="mb-3">
                          <h5>Statut</h5>
                          <select class="form-select" v-model="clientDansPopup.cliValide" :disabled="!clientDansPopup.editMode">
                            <option value="0">Prospect</option>
                            <option value="1">Client</option>
                          </select>
                        </div>

                        <div class="mb-3">
                          <h5>Description</h5>
                          <textarea class="form-control" v-model="clientDansPopup.cliDescription" :disabled="!clientDansPopup.editMode"></textarea>
                        </div>

                        <div class="mb-3">
                          <h5>Document</h5>
                          <div class="row">
                            <div class="col">
                              <div class="d-flex flex-wrap" v-if="clientDansPopup.cliDocOC">
                                <button @click="openDococ(clientDansPopup.cliDocOC)"
                                  class="btn btn-primary">
                                  PDF
                                </button>
                              </div>
                              <div class="d-flex flex-wrap" v-else>
                                <span>Aucun document</span>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="mb-3">
                          <h5>Photo(s)</h5>
                          <div class="row">
                            <div class="col">
                              <div class="d-flex flex-wrap" v-if="clientDansPopup.cliPhotos.length > 0">
                                <button v-for="photo in clientDansPopup.cliPhotos" :key="photo.photoNom"
                                  @click="openImage(photo.photoNom)" class="btn btn-outline-primary me-2 mb-2">
                                  Photo
                                </button>
                              </div>
                              <div class="d-flex flex-wrap" v-else>
                                <span>Aucune photo</span>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="mb-3">
                            <h5>Action</h5>
                            <div class="d-grid gap-2">
                              <button class="btn btn-primary" v-if="clientDansPopup.editMode"
                                @click="saveClient(clientDansPopup)">
                                <i class="fas fa-save me-1"></i> Enregistrer
                              </button>
                              <button class="btn btn-secondary" v-if="!clientDansPopup.editMode"
                                @click="editClient(clientDansPopup)">
                                <i class="fas fa-edit me-1"></i> Modifier
                              </button>
                              <button class="btn btn-danger" @click="deleteClient(clientDansPopup)">
                                <i class="fas fa-trash me-1"></i> Supprimer
                              </button>
                            </div>
                          </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer"></div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<style scoped>
/* Style pour les boutons Enregistrer et Modifier */
button.save,
button.edit {
  background-color: #4caf50;
  /* Couleur verte */
  color: white;
  padding: 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.3s ease;
  margin: 5px;
}

/* Style pour le bouton Supprimer */
button.delete {
  background-color: #f44336;
  /* Couleur rouge */
  color: white;
  padding: 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.3s ease;
  margin: 5px;
}

/* Style au survol des boutons */
button:hover {
  opacity: 0.8;
}

/* Alignement des boutons */
.button-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

#app {
  height: 100vh;
}

h1 {
  font-size: 3.2em;
  line-height: 1.1;
}

h2 {
  color: rgb(0, 0, 0) !important;
  font-size: 50px !important;
  margin-bottom: 10px !important;
}
</style>

<script>
export default {
  data() {
    return {
      clients: [],
      clientDansPopup: null,
    };
  },
  mounted() {
    // Au chargement du composant, récupérez les clients depuis le serveur
    this.getClients();
  },
  methods: {
    getClients() {
      // Créer un nouvel objet FormData
      const formData = new FormData();
      formData.append("userName", this.$store.state.userInfo.userName);
      formData.append("userPassword", this.$store.state.userInfo.userPassword);

      // Envoyez une requête fetch pour récupérer les clients depuis votre serveur PHP
      fetch(`${import.meta.env.VITE_BACKEND_URL}/clients.php`, {
        method: "POST",
        body: formData, // Utiliser l'objet FormData comme corps de la requête
      })
        .then((response) => response.json())
        .then((data) => {
          // Initialiser la propriété editMode pour chaque client
          this.clients = data.map((client) => {
            return { ...client, editMode: false };
          });
        })
        .catch((error) => {
          console.error("Erreur lors de la récupération des clients:", error);
        });
    },
    getTypeName(typeID) {
      // Méthode pour obtenir le nom du type d'intervention à partir de son ID
      const types = {
        1: "Événementiel",
        2: "Résidentiel",
        3: "Tertiaire",
        4: "Industriel",
      };
      return types[typeID] || "Inconnu";
    },
    getValidText(validID) {
      // Méthode pour obtenir le nom du type d'intervention à partir de son ID
      const types = {
        0: "Prospect",
        1: "Client",
      };
      return types[validID] || "Inconnu";
    },
    openDococ(dococFileName) {
      if (!dococFileName) {
        alert("Aucun document");
        return;
      }
      window.open(this.getDococUrl(dococFileName), "_blank");
    },
    openImage(photoName) {
      if (!photoName) {
        alert("Aucune photo");
        return;
      }
      window.open(this.getPhotoUrl(photoName), "_blank");
    },
    getDococUrl(fileName) {
      // Méthode pour obtenir l'URL de la photo du client à partir de son ID
      return `${import.meta.env.VITE_BACKEND_URL}/client/documents/${fileName}`; // Assurez-vous que le format de l'image est correct
    },
    getPhotoUrl(photoID) {
      // Méthode pour obtenir l'URL de la photo du client à partir de son ID
      return `${import.meta.env.VITE_BACKEND_URL}/client/photos/${photoID}`; // Assurez-vous que le format de l'image est correct
    },
    openPopup(c) {
      this.clientDansPopup = c;
    },
    closePopup() {
      this.clientDansPopup = null;
    },
    editClient(client) {
      // Activer le mode édition pour ce client
      client.editMode = true;
    },
    saveClient(client) {
      console.log(this.$store.state.userInfo);

      // Créer un nouvel objet FormData
      const formData = new FormData();

      // Ajouter les données du client à l'objet FormData
      for (const key in client) {
        formData.append(key, client[key]);
      }

      formData.append("userName", this.$store.state.userInfo.userName);
      formData.append("userPassword", this.$store.state.userInfo.userPassword);

      // Envoyer une requête POST pour mettre à jour le client sur le serveur
      fetch(`${import.meta.env.VITE_BACKEND_URL}/updateClient.php`, {
        method: "POST",
        body: formData, // Utiliser l'objet FormData comme corps de la requête
      })
        .then((response) => {
          if (response.ok) {
            console.log("Client mis à jour avec succès :", client);
            console.log(response.text());
            // Rafraîchir la liste des clients après la mise à jour
            this.getClients();
            // Désactiver le mode édition
            client.editMode = false;
          } else {
            console.error(
              "Erreur lors de la mise à jour du client :",
              response.statusText
            );
          }
        })
        .catch((error) => {
          console.error("Erreur lors de la mise à jour du client :", error);
        });
    },
    deleteClient(client) {
      console.log(this.$store.state.userInfo);

      // Créer un nouvel objet FormData
      const formData = new FormData();
      formData.append("cliID", client.cliID);
      formData.append("userName", this.$store.state.userInfo.userName);
      formData.append("userPassword", this.$store.state.userInfo.userPassword);

      // Confirmer avec l'utilisateur avant de supprimer le client
      if (confirm("Êtes-vous sûr de vouloir supprimer ce client ?")) {
        // Envoyer une requête DELETE pour supprimer le client
        fetch(`${import.meta.env.VITE_BACKEND_URL}/deleteClient.php`, {
          method: "POST",
          body: formData, // Utiliser l'objet FormData comme corps de la requête
        })
          .then((response) => {
            if (response.ok) {
              // Supprimer le client de la liste clients
              const index = this.clients.findIndex(
                (c) => c.cliID === client.cliID
              );
              if (index !== -1) {
                this.clients.splice(index, 1);
                console.log("Client supprimé avec succès:", client);
                this.closePopup();
              } else {
                console.error("Client non trouvé dans la liste:", client);
              }
            } else {
              console.error(
                "Erreur lors de la suppression du client:",
                response.statusText
              );
            }
          })
          .catch((error) => {
            console.error("Erreur lors de la suppression du client:", error);
          });
      }
    },
    logout() {
      this.$cookies.remove("loginCookie");
      this.$store.commit("setLoggedIn", false);
      this.$store.commit("setUserInfo", {});
      this.$router.push("/connexion");
    },
  },
};
</script>
