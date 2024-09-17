<script setup></script>

<template>
  <div class="d-flex flex-column h-100">
    <header>
      <!-- Fixed navbar -->
      <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark"
        style="background: linear-gradient(90deg, #808080 10%, #0077ff 80%);">
        <div class="container-fluid">
          <a class="navbar-brand text-dark" href="#">Agami-Light</a>
          <button class="navbar-toggler bg-transparent" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link text-dark" href="#secteur">A propos</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-dark" href="#evenements">Evenements</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-dark" href="#">Contact</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </header>

    <!-- Begin page content -->
    <main>
      <!--Image Header-->
      <div class="headerimg"></div>

      <!--Bouton Formulaire de contact-->
      <div class="button-container">
        <button id="popup-button" type="button" class="btn btn-primary btn-lg mt-2 mb-0 py-3 px-4></button>"
          @click="showFormContactPopup()">
          Me Contacter
        </button>
      </div>
      <div id="secteur"></div>

      <!-- Formulaire De Contact -->
      <div v-show="formContactPopupVisible" class="modal show" tabindex="-1" role="dialog"
        style="display: block; background-color: rgba(0, 0, 0, 0.5)">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="multiPageFormModalLabel">
                Formulaire de contact
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                @click="hideFormContactPopup()">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form id="form" method="post" enctype="multipart/form-data" @submit.prevent="">
                <div v-show="currentPage === 1">
                  <h5>Informations personelles</h5>
                  <div class="form-group">
                    <label for="sexe">Sexe :</label>
                    <select id="sexe" v-model="sexe" required>
                      <option value="Homme">Homme</option>
                      <option value="Femme">Femme</option>
                      <option value="Autres">Autres</option>
                    </select>
                    <div v-if="errors.sexe" class="text-danger">
                      {{ errors.sexe }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" v-model="nom" required />
                    <div v-if="errors.nom" class="text-danger">
                      {{ errors.nom }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="prenom">Prénom :</label>
                    <input type="text" id="prenom" v-model="prenom" required />
                    <div v-if="errors.prenom" class="text-danger">
                      {{ errors.prenom }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="mail">E-mail :</label>
                    <input type="email" id="mail" v-model="mail" required />
                    <div v-if="errors.mail" class="text-danger">
                      {{ errors.mail }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="numero">Numéro de téléphone :</label>
                    <input type="text" id="numero" v-model="numero" required />
                    <div v-if="errors.numero" class="text-danger">
                      {{ errors.numero }}
                    </div>
                  </div>
                </div>
                <div v-show="currentPage === 2">
                  <h5>Détails de l'intervention</h5>
                  <div class="form-group">
                    <label for="type">Type d'intervention :</label>
                    <select id="type" v-model="type" @change="soustype = ''" required>
                      <option value="1">Événementiel</option>
                      <option value="2">Résidentiel</option>
                      <option value="3">Tertiaire</option>
                      <option value="4">Industriel</option>
                    </select>
                    <div v-if="errors.type" class="text-danger">
                      {{ errors.type }}
                    </div>
                  </div>
                  <div v-if="['2', '3', '4'].includes(type)" class="form-group">
                    <label for="type">Sous-type d'intervention :</label>
                    <select id="soustype" v-model="soustype" required>
                      <option value="1">Nouvelle installation</option>
                      <option value="2">Remise en conformité</option>
                      <option value="3">Rénovation</option>
                      <option value="4">Dépanage</option>
                      <option value="5" v-show="type != '2'">
                        Service de maintenance
                      </option>
                    </select>
                    <div v-if="errors.soustype" class="text-danger">
                      {{ errors.soustype }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Choisissez la date et le créneaux d'intervention :</label>
                    <div v-for="(entry, index) in dateEntries" :key="index"
                      class="date-entry d-flex flex-column flex-sm-row align-items-start gap-2 mb-3">
                      <Datepicker v-model="entry.date" language="fr" :disabled-dates="disabledDates" />

                      <div class="unpepene d-flex" style="height: 44px">
                        <input type="time" v-model="entry.startTime" @change="validateStartTime(index)"
                          class="form-control" style="width: 100px;" />

                        <input type="time" v-model="entry.endTime" @change="validateEndTime(index)" class="form-control"
                          style="width: 100px;" />

                        <span v-if="entry.error" class="text-danger">{{
                          entry.error
                          }}</span>

                        <!-- Bouton de suppression pour toutes les entrées sauf la première -->
                        <button v-if="dateEntries.length > 1" type="button" @click="removeEntry(index)"
                          class="btn btn-danger">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </div>
                    </div>
                    <button v-if="dateEntries.length === 0 || dateEntries.length < 5" type="button" @click="addEntry"
                      :disabled="!canAddEntry" class="btn btn-primary">
                      <i class="fas fa-plus"></i>
                    </button>
                    <div v-if="errors.dateEntries" class="text-danger">
                      {{ errors.dateEntries }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="description">Description :</label>
                    <textarea id="description" v-model="description" maxlength="1000" required></textarea>
                    <span>{{ description.length }}/1000</span>
                    <div v-if="errors.description" class="text-danger">
                      {{ errors.description }}
                    </div>
                  </div>
                </div>
                <div v-show="currentPage === 3">
                  <h5>Adresse et fichiers</h5>
                  <div class="form-group">
                    <label for="rue">Rue et n° :</label>
                    <input type="text" id="rue" v-model="rue" required />
                    <div v-show="errors.rue" class="text-danger">
                      {{ errors.rue }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="ville">Ville et code postal :</label>
                    <input type="text" id="ville" v-model="ville" required />
                    <div v-show="errors.ville" class="text-danger">
                      {{ errors.ville }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Document d'organisme de contrôle :</label>
                    <label>
                      <input type="radio" name="dococ" value="false" v-model="dococ" checked required />
                      Non
                    </label>
                    <label>
                      <input type="radio" name="dococ" value="true" v-model="dococ" required />
                      Oui
                    </label>
                    <input v-show="dococ == 'true'" type="file" id="dococFile" name="dococFile" accept="application/pdf"
                      @change="handleDococFileChange" />
                    <div v-if="errors.dococ" class="text-danger">
                      {{ errors.dococ }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="photo">Importer une photo :</label>
                    <input class="selec-img" type="file" id="photo" name="photo" accept="image/*" multiple
                      @change="handleFileChange" />
                    <div v-if="errors.photos" class="text-danger">
                      {{ errors.photos }}
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" v-show="currentPage > 1" @click="prevPage">
                Précédent
              </button>
              <button type="button" class="btn btn-primary" v-show="currentPage < 3" @click="nextPage">
                Suivant
              </button>
              <button type="button" class="btn btn-success" v-show="currentPage == 3" @click="nextPage" :disabled="sending">
                <span v-if="sending">
                  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  Envoi...
                </span>
                <span v-else>Soumettre</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Popup message succes -->
      <div v-show="successPopupVisible" class="modal show" tabindex="-1" role="dialog"
        style="display: block; background-color: rgba(0, 0, 0, 0.5)">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="messageModalLabel">Notification</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeSuccessPopup()">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              Votre message a été envoyé a Agami-Light.
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeSuccessPopup()">
                Fermer
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Popup message erreur -->
      <div v-show="errorPopupVisible" class="modal show" tabindex="-1" role="dialog"
        style="display: block; background-color: rgba(0, 0, 0, 0.5)">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="errorModalLabel">Erreur</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeErrorPopup()">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              {{ errors.form }}
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeErrorPopup()">
                Fermer
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- TrustBox widget - Micro Review Count -->

      <div id="fixedDiv" style="cursor: pointer"
        onclick="window.open('https://fr.trustpilot.com/review/agami-light.be', '_blank');">
        <div class="trustpilot-widget" data-locale="fr-FR" data-template-id="5419b6a8b0d04a076446a9ad"
          data-businessunit-id="64943c9139c073f387d3f3ac" data-style-height="24px" data-style-width="100%"
          data-theme="light" data-min-review-count="10" data-style-alignment="center">
          <a href="https://fr.trustpilot.com/review/agami-light.be" target="_blank" rel="noopener">Trustpilot</a>
        </div>
      </div>

      <!-- End TrustBox widget -->
      <!---A propos-->
      <div class="electrician-container mt-5 pt-5 pb-5">
        <div class="electrician-content">
          <div class="electrician-img-content">
            <img class="electrician-image" />
          </div>
          <div class="electrician-text">
            <h1 class="electrician-title">A propos</h1>
            <p class="electrician-description">
              Je m'appelle Justin Walravens et je suis électricien professionnel
              spécialisé dans les secteurs résidentiel, industriel et
              événementiel. Avec plusieurs années d'expérience dans le domaine,
              j'ai développé une expertise approfondie et une passion pour
              offrir des solutions électriques fiables et innovantes. Que ce
              soit pour l'installation de bornes de recharge pour véhicules
              électriques, la mise en place de systèmes de domotique avancés, la
              remise en conformité de vos installations électriques ou la
              gestion complète de l'électricité lors d'événements, je suis à
              votre service pour répondre à tous vos besoins électriques.
            </p>
            <h6>Mes services : <br /></h6>
            <p class="electrician-description">
              Installation de bornes de recharge : Je propose des solutions de
              recharge adaptées à tous types de véhicules électriques,
              garantissant une installation sécurisée et efficace.
              <br />Domotique : Je transforme votre maison en un espace
              intelligent avec des systèmes de contrôle automatisé pour un
              confort et une sécurité accrus. <br />Remise en conformité : Je
              m'assure que vos installations électriques respectent les normes
              en vigueur, pour une sécurité optimale. <br />Gestion électrique
              événementielle : J'assure une gestion électrique complète pour vos
              événements, garantissant une alimentation stable et sécurisée pour
              tous vos équipements. <br /><br />Mon objectif est de fournir un
              service de qualité, personnalisé et fiable pour chaque projet.
              N'hésitez pas à me contacter pour discuter de vos besoins
              spécifiques et découvrir comment je peux vous aider à réaliser vos
              projets électriques.
            </p>
          </div>
        </div>
      </div>
      <!--ICONES-->
      <div class="container d-none d-lg-flex d-sm-block d-md-block" id="accordionExample">
        <div class="row justify-content-center accordion" id="accordionExample">
          <div class="col-12 col-sm-6 col-md-4 custom-div">
            <!--Résidentiel-->
            <div class="icon col-sm d-flex flex-column align-items-center">
              <button class="img-container" type="button" data-bs-toggle="collapse"
                data-bs-target="#multiCollapseExample1" aria-expanded="false" aria-controls="multiCollapseExample1">
                <img src="/images/Icones/house-solid (1).svg" alt="Maison" />
              </button>
              <div class="description text-center mt-2">Résidentiel</div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 custom-div">
            <!--Tertiaire et Industriel-->
            <div class="icon col-sm d-flex flex-column align-items-center">
              <button class="img-container" type="button" data-bs-toggle="collapse"
                data-bs-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2">
                <img src="/images/Icones/building-solid.svg" alt="Bâtiments" />
              </button>
              <div class="description text-center mt-2">
                Tertiaire - Industriel
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 custom-div">
            <!--Evenementiel-->
            <div class="icon col-sm d-flex flex-column align-items-center">
              <button class="img-container" type="button" data-bs-toggle="collapse"
                data-bs-target="#multiCollapseExample3" aria-expanded="false" aria-controls="multiCollapseExample3">
                <img src="/images/Icones/stadium_24dp_FILL0_wght400_GRAD0_opsz24.svg" alt="Stade" />
              </button>
              <div class="description text-center mt-2">Evénementiel</div>
            </div>
          </div>
        </div>
        <!--COLLAPSE 1 -->

        <!--Carousel Résidentiel-->
        <div class="collapse container" id="multiCollapseExample1" data-bs-parent="#accordionExample">
          <div class="accordion-body d-flex justify-content-center">
            <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel" style="">
              <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                  aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                  aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                  aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="3"
                  aria-label="Slide 4"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="4"
                  aria-label="Slide 5"></button>
              </div>
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <img src="/images/Carousel/Residentiel/residentiel_image1.jpg" class="d-block w-100" alt="Slide 1" />
                  <div class="carousel-caption d-none d-md-block"></div>
                </div>
                <div class="carousel-item">
                  <img src="/images/Carousel/Residentiel/residentiel_image2.jpg" class="d-block w-100" alt="Slide 2" />
                  <div class="carousel-caption d-none d-md-block"></div>
                </div>
                <div class="carousel-item">
                  <img src="/images/Carousel/Residentiel/residentiel_image3.jpg" class="d-block w-100" alt="Slide 3" />
                  <div class="carousel-caption d-none d-md-block"></div>
                </div>
                <div class="carousel-item">
                  <img src="/images/Carousel/Residentiel/residentiel_image4.jpg" class="d-block w-100" alt="Slide 4" />
                  <div class="carousel-caption d-none d-md-block"></div>
                </div>
                <div class="carousel-item">
                  <img src="/images/Carousel/Residentiel/residentiel_image5.jpg" class="d-block w-100" alt="Slide 5" />
                  <div class="carousel-caption d-none d-md-block"></div>
                </div>
              </div>

              <button class="carousel-control-prev bg-transparent" type="button"
                data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next bg-transparent" type="button"
                data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div>
        </div>
        <!--COLLAPSE 2-->
        <!--Carousel tertiaire - industriel-->
        <div class="collapse container" id="multiCollapseExample2" data-bs-parent="#accordionExample">
          <div class="accordion-body d-flex justify-content-center">
            <div id="carouselExampleCaptions2" class="carousel slide" data-bs-ride="carousel" style="">
              <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions2" data-bs-slide-to="0" class="active"
                  aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions2" data-bs-slide-to="1"
                  aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions2" data-bs-slide-to="2"
                  aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions2" data-bs-slide-to="3"
                  aria-label="Slide 4"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions2" data-bs-slide-to="4"
                  aria-label="Slide 5"></button>
              </div>
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <img src="/images/Carousel/Industriel/industriel_image1.jpg" class="d-block w-100" alt="Slide 1" />
                  <div class="carousel-caption d-none d-md-block"></div>
                </div>
                <div class="carousel-item">
                  <img src="/images/Carousel/Industriel/industriel_image2.jpg" class="d-block w-100" alt="Slide 2" />
                  <div class="carousel-caption d-none d-md-block"></div>
                </div>
                <div class="carousel-item">
                  <img src="/images/Carousel/Industriel/industriel_image3.jpg" class="d-block w-100" alt="Slide 3" />
                  <div class="carousel-caption d-none d-md-block"></div>
                </div>
                <div class="carousel-item">
                  <img src="/images/Carousel/Industriel/industriel_image4.jpg" class="d-block w-100" alt="Slide 4" />
                  <div class="carousel-caption d-none d-md-block"></div>
                </div>
                <div class="carousel-item">
                  <img src="/images/Carousel/Industriel/industriel_image5.jpg" class="d-block w-100" alt="Slide 5" />
                  <div class="carousel-caption d-none d-md-block"></div>
                </div>
              </div>

              <button class="carousel-control-prev bg-transparent" type="button"
                data-bs-target="#carouselExampleCaptions2" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next bg-transparent" type="button"
                data-bs-target="#carouselExampleCaptions2" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div>
        </div>
        <!--COLLAPSE 3-->
        <!--Carousel Evenementiel-->
        <div class="collapse container" id="multiCollapseExample3" data-bs-parent="#accordionExample">
          <div class="accordion-body d-flex justify-content-center">
            <div id="carouselExampleCaptions3" class="carousel slide" data-bs-ride="carousel" style="">
              <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions3" data-bs-slide-to="0" class="active"
                  aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions3" data-bs-slide-to="1"
                  aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions3" data-bs-slide-to="2"
                  aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions3" data-bs-slide-to="3"
                  aria-label="Slide 4"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions3" data-bs-slide-to="4"
                  aria-label="Slide 5"></button>
              </div>
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <img src="/images/Carousel/Evenementiel/evenementiel_image1.jpg" class="d-block w-100"
                    alt="Slide 1" />
                  <div class="carousel-caption d-none d-md-block"></div>
                </div>
                <div class="carousel-item">
                  <img src="/images/Carousel/Evenementiel/evenementiel_image2.jpg" class="d-block w-100"
                    alt="Slide 2" />
                  <div class="carousel-caption d-none d-md-block"></div>
                </div>
                <div class="carousel-item">
                  <img src="/images/Carousel/Evenementiel/evenementiel_image3.jpg" class="d-block w-100"
                    alt="Slide 3" />
                  <div class="carousel-caption d-none d-md-block"></div>
                </div>
                <div class="carousel-item">
                  <img src="/images/Carousel/Evenementiel/evenementiel_image4.jpg" class="d-block w-100"
                    alt="Slide 4" />
                  <div class="carousel-caption d-none d-md-block"></div>
                </div>
                <div class="carousel-item">
                  <img src="/images/Carousel/Evenementiel/evenementiel_image5.jpg" class="d-block w-100"
                    alt="Slide 5" />
                  <div class="carousel-caption d-none d-md-block"></div>
                </div>
              </div>

              <button class="carousel-control-prev bg-transparent" type="button"
                data-bs-target="#carouselExampleCaptions3" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next bg-transparent" type="button"
                data-bs-target="#carouselExampleCaptions3" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div>
        </div>
      </div>
      <!-- ICONES phone!!!-->
      <div class="container-fluid d-lg-none d-md-none d-sm-none" id="accordionExample">
        <div class="row justify-content-center accordion" id="accordionExample">
          <div class="col-12 col-sm-6 col-md-4 custom-div">
            <!--Résidentiel-->
            <div class="icon2 col-sm d-flex flex-column align-items-center">
              <button class="img-container2" type="button" data-bs-toggle="collapse"
                data-bs-target="#multiCollapseExample4" aria-expanded="false" aria-controls="multiCollapseExample4">
                <img src="/images/Icones/house-solid (1).svg" alt="Maison" />
              </button>
              <div class="description text-center mt-2">Résidentiel</div>
            </div>
            <!--COLLAPSE 4-->
            <!--Carousel Residentiel phone-->
            <div class="collapse container" id="multiCollapseExample4" data-bs-parent="#accordionExample">
              <div class="accordion-body d-flex justify-content-center">
                <div id="carouselExampleCaptions4" class="carousel slide" data-bs-ride="carousel" style="">
                  <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleCaptions4" data-bs-slide-to="0" class="active"
                      aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions4" data-bs-slide-to="1"
                      aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions4" data-bs-slide-to="2"
                      aria-label="Slide 3"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions4" data-bs-slide-to="3"
                      aria-label="Slide 4"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions4" data-bs-slide-to="4"
                      aria-label="Slide 5"></button>
                  </div>
                  <div class="carousel-inner">
                    <div class="carousel-item active">
                      <img src="/images/Carousel/Residentiel/residentiel_image1.jpg" class="d-block w-100"
                        alt="Slide 1" />
                      <div class="carousel-caption d-none d-md-block"></div>
                    </div>
                    <div class="carousel-item">
                      <img src="/images/Carousel/Residentiel/residentiel_image2.jpg" class="d-block w-100"
                        alt="Slide 2" />
                      <div class="carousel-caption d-none d-md-block"></div>
                    </div>
                    <div class="carousel-item">
                      <img src="/images/Carousel/Residentiel/residentiel_image3.jpg" class="d-block w-100"
                        alt="Slide 3" />
                      <div class="carousel-caption d-none d-md-block"></div>
                    </div>
                    <div class="carousel-item">
                      <img src="/images/Carousel/Residentiel/residentiel_image4.jpg" class="d-block w-100"
                        alt="Slide 4" />
                      <div class="carousel-caption d-none d-md-block"></div>
                    </div>
                    <div class="carousel-item">
                      <img src="/images/Carousel/Residentiel/residentiel_image5.jpg" class="d-block w-100"
                        alt="Slide 5" />
                      <div class="carousel-caption d-none d-md-block"></div>
                    </div>
                  </div>

                  <button class="carousel-control-prev bg-transparent" type="button"
                    data-bs-target="#carouselExampleCaptions4" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                  </button>
                  <button class="carousel-control-next bg-transparent" type="button"
                    data-bs-target="#carouselExampleCaptions4" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 custom-div">
            <!--Tertiaire et Industriel-->
            <div class="icon2 col-sm d-flex flex-column align-items-center">
              <button class="img-container2" type="button" data-bs-toggle="collapse"
                data-bs-target="#multiCollapseExample5" aria-expanded="false" aria-controls="multiCollapseExample5">
                <img src="/images/Icones/building-solid.svg" alt="Bâtiments" />
              </button>
              <div class="description text-center mt-2">
                Tertiaire - Industriel
              </div>
            </div>
            <!--COLLAPSE 5-->
            <!--Carousel tertiare - indstriel phone-->
            <div class="collapse container" id="multiCollapseExample5" data-bs-parent="#accordionExample">
              <div class="accordion-body d-flex justify-content-center">
                <div id="carouselExampleCaptions5" class="carousel slide" data-bs-ride="carousel" style="">
                  <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleCaptions5" data-bs-slide-to="0" class="active"
                      aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions5" data-bs-slide-to="1"
                      aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions5" data-bs-slide-to="2"
                      aria-label="Slide 3"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions5" data-bs-slide-to="3"
                      aria-label="Slide 4"></button>
                    <button type="button" data-bs-target="#carouselExampleCaptions5" data-bs-slide-to="4"
                      aria-label="Slide 5"></button>
                  </div>
                  <div class="carousel-inner">
                    <div class="carousel-item active">
                      <img src="/images/Carousel/Industriel/industriel_image1.jpg" class="d-block w-100"
                        alt="Slide 1" />
                      <div class="carousel-caption d-none d-md-block"></div>
                    </div>
                    <div class="carousel-item">
                      <img src="/images/Carousel/Industriel/industriel_image2.jpg" class="d-block w-100"
                        alt="Slide 2" />
                      <div class="carousel-caption d-none d-md-block">
                        <h5>Second slide label</h5>
                      </div>
                    </div>
                    <div class="carousel-item">
                      <img src="/images/Carousel/Industriel/industriel_image3.jpg" class="d-block w-100"
                        alt="Slide 3" />
                      <div class="carousel-caption d-none d-md-block">
                        <h5>Third slide label</h5>
                      </div>
                    </div>
                    <div class="carousel-item">
                      <img src="/images/Carousel/Industriel/industriel_image4.jpg" class="d-block w-100"
                        alt="Slide 4" />
                      <div class="carousel-caption d-none d-md-block"></div>
                    </div>
                    <div class="carousel-item">
                      <img src="/images/Carousel/Industriel/industriel_image5.jpg" class="d-block w-100"
                        alt="Slide 5" />
                      <div class="carousel-caption d-none d-md-block"></div>
                    </div>
                  </div>

                  <button class="carousel-control-prev bg-transparent" type="button"
                    data-bs-target="#carouselExampleCaptions5" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                  </button>
                  <button class="carousel-control-next bg-transparent" type="button"
                    data-bs-target="#carouselExampleCaptions5" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-4 custom-div">
            <!--Evenementiel-->
            <div class="icon2 col-sm d-flex flex-column align-items-center">
              <button class="img-container2" type="button" data-bs-toggle="collapse"
                data-bs-target="#multiCollapseExample6" aria-expanded="false" aria-controls="multiCollapseExample6">
                <img src="/images/Icones/stadium_24dp_FILL0_wght400_GRAD0_opsz24.svg" alt="Stade" />
              </button>
              <div class="description text-center mt-2">Evénementiel</div>
            </div>
          </div>
          <!--COLLAPSE 6-->
          <!--Carousel Evenementiel phone-->
          <div class="collapse container" id="multiCollapseExample6" data-bs-parent="#accordionExample">
            <div class="accordion-body d-flex justify-content-center">
              <div id="carouselExampleCaptions6" class="carousel slide" data-bs-ride="carousel" style="">
                <div class="carousel-indicators">
                  <button type="button" data-bs-target="#carouselExampleCaptions6" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                  <button type="button" data-bs-target="#carouselExampleCaptions6" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                  <button type="button" data-bs-target="#carouselExampleCaptions6" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
                  <button type="button" data-bs-target="#carouselExampleCaptions6" data-bs-slide-to="3"
                    aria-label="Slide 4"></button>
                  <button type="button" data-bs-target="#carouselExampleCaptions6" data-bs-slide-to="4"
                    aria-label="Slide 5"></button>
                </div>
                <div class="carousel-inner">
                  <div class="carousel-item active">
                    <img src="/images/Carousel/Evenementiel/evenementiel_image1.jpg" class="d-block w-100"
                      alt="Slide 1" />
                    <div class="carousel-caption d-none d-md-block"></div>
                  </div>
                  <div class="carousel-item">
                    <img src="/images/Carousel/Evenementiel/evenementiel_image2.jpg" class="d-block w-100"
                      alt="Slide 2" />
                    <div class="carousel-caption d-none d-md-block"></div>
                  </div>
                  <div class="carousel-item">
                    <img src="/images/Carousel/Evenementiel/evenementiel_image3.jpg" class="d-block w-100"
                      alt="Slide 3" />
                    <div class="carousel-caption d-none d-md-block"></div>
                  </div>
                  <div class="carousel-item">
                    <img src="/images/Carousel/Evenementiel/evenementiel_image4.jpg" class="d-block w-100"
                      alt="Slide 4" />
                    <div class="carousel-caption d-none d-md-block"></div>
                  </div>
                  <div class="carousel-item">
                    <img src="/images/Carousel/Evenementiel/evenementiel_image5.jpg" class="d-block w-100"
                      alt="Slide 5" />
                    <div class="carousel-caption d-none d-md-block"></div>
                  </div>
                </div>

                <button class="carousel-control-prev bg-transparent" type="button"
                  data-bs-target="#carouselExampleCaptions6" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next bg-transparent" type="button"
                  data-bs-target="#carouselExampleCaptions6" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--Callendrier-->
      <div id="evenements" class="fullscreen-div second-div">
        <iframe width="100%" height="600"
          src="https://nextcloud.agami-light.be/index.php/apps/calendar/embed/BoMb7SAXFNwLELsY"></iframe>
      </div>
    </main>
    <!--Footer-->
    <footer id="footer" class="footer mt-auto py-2 bg-primary">
      <div class="container-fluid">
        <div class="row align-items-center">
          <div class="col-4 text-start">
            <p class="mb-0 small">2024 Agami-Light.be</p>
            <!-- Ajout de la classe "small" pour réduire la taille de la police -->
          </div>
          <div class="col-4 text-center">
            <img src="/images/Footer/LOGO_NEGATIF.png" alt="Logo" class="img-fluid rounded" style="width: 60px" />
          </div>
          <div class="col-4 text-end">
            <a href="mentions" class="text-dark">Mentions légales</a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
.headerimg {
  margin-top: 50px;
  background-image: url(/images/Header/imgheader.jpg);
  height: 40vh;
  background-position: center;
  background-size: cover;
  background-repeat: no-repeat;
}

#fixedDiv {
  position: fixed;
  right: 10px;
  bottom: 20px;
  z-index: 1000;
  max-width: 300px;
  width: 350px;
  height: auto;
  background-color: #ffffff;
  border-radius: 5px !important;
  box-shadow: 0px 0px 21px 3px rgb(0, 0, 0) !important;
  padding: 10px !important;
}

/* Style optionnel pour la div */

body {
  margin: 0;
  padding: 0;
  /* Vos styles existants */
  display: flex;
  place-items: center;
  min-width: 320px;
  min-height: 100vh;
}

/* CSS personnalisé pour agrandir le bouton */
#popup-button {
  /* Ajustez la taille du padding selon vos besoins */
  font-size: 20px;
  /* Ajustez la taille de la police selon vos besoins */
}

/* Variables globales */
:root {
  /* Couleurs */
  --primary-color: #0077ff;
  --secondary-color: #646cff;
  --text-color: #ffffff;
  --background-color: #242424;

  /* Polices */
  --font-primary: Inter, system-ui, Avenir, Helvetica, Arial, sans-serif;
}

/* Liens */
a {
  font-weight: 500;
  color: var(--secondary-color);
  text-decoration: inherit;
}

a:hover {
  color: #535bf2;
}

/* Boutons */
button {
  border-radius: 8px;
  border: 1px solid transparent;
  padding: 0.6em 1.2em;
  font-size: 1em;
  font-weight: 500;
  font-family: inherit;
  background-color: #1a1a1a;
  cursor: pointer;
  transition: border-color 0.25s;
}

.button-container {
  text-align: center;
}

button:focus,
button:focus-visible {
  outline: 4px auto -webkit-focus-ring-color;
}

/* En-têtes */
h1 {
  font-size: 3.2em;
  line-height: 1.1;
}

h2 {
  color: white !important;
  /* Couleur du texte */
  font-size: 50px !important;
  /* Taille de la police */
  margin-bottom: 10px !important;
  /* Marge inférieure */
}

/* Pour agrandir la taille de la police du paragraphe spécifique */
.bigger-font-size {
  font-size: 1.2em;
  /* Changer la taille de la police selon vos besoins */
}

/* Rectangles */
.rectangle {
  background-color: #f0f0f0;
  /* Couleur de fond gris clair */
  width: 30vw;
  height: 9vh;
  text-align: center;
  border-radius: 20px;
  /* Coins plus arrondis */
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
  /* Ombre légère */
  margin: 0 auto;
  /* Pour centrer le rectangle horizontalement */
  margin-bottom: 2vh;
  transition: all 0.3s ease;
  /* Animation de transition fluide */
}

.rectangle:hover {
  transform: translateY(-5px);
  /* Légère translation vers le haut au survol */
  box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.2);
  /* Ombre légèrement plus prononcée au survol */
}

.rectangle p {
  font-size: 2.2vh;
  /* Taille de police adaptative */
  margin: 0;
  padding: 2vh 0;
  /* Espacement intérieur adaptatif */
  white-space: nowrap;
  color: #333;
  /* Couleur de texte gris foncé */
  font-weight: bold;
  /* Police en gras */
}

.content {
  max-width: 90%;
  /* Augmenter légèrement la largeur max */
  margin: 5vh auto;
  /* Espacement plus important et centrage horizontal */
  text-align: left;
  /* Alignement du texte à gauche pour un style plus contemporain */
  padding: 2vh;
  /* Ajout de rembourrage pour l'espace interne */
  background-color: #0077ff;
  /* Fond blanc */
  border-radius: 10px;
  /* Coins arrondis */
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
  /* Ombre légère */
}

/* Icônes */

/* Styles pour la section du carrousel et du texte */
.row {
  margin-bottom: 5vh;
  /* Espacement inférieur pour séparer les sections */
}

/* Customisation de la div contenant le carrousel */
.custom-div {
  padding: 2vh;
  /* Ajout de rembourrage */
}

/* Styles pour le texte */
.custom-content {
  background-color: #0077ff;
  /* Couleur de fond */
}

.custom-content .content {
  padding: 2vh;
  /* Ajout de rembourrage */
}

.custom-content h2 {
  color: #fff;
  /* Couleur de texte blanc */
  font-size: 2.5vh;
  /* Taille de police adaptative */
}

.custom-content p {
  color: #fff;
  /* Couleur de texte blanc */
  font-size: 1.8vh;
  /* Taille de police adaptative */
  margin: 2vh;
  /* Espacement intérieur */
}

/* Ajouter ces styles pour déplacer le h2 au-dessus du paragraphe */
.col-md-6 {
  display: flex;
  flex-direction: column;
  margin-bottom: 5%;
}

.col-md-6 h2 {
  order: -1;
}

/* Footer */
.footer {
  background-color: #0077ff;
  /* Changement de la couleur de fond */
  padding: 0px;
  color: #343a40;
}

.smaller-image {
  width: 50%;
  height: auto;
}

/* Styles pour le formulaire */

.popup-overlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 9999999;
}

.popup-content {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
  max-height: 80%;
  overflow-y: auto;
  width: 80%;
  /* Ajoutez cette ligne et ajustez la valeur selon vos besoins */
  max-width: 500px;
  /* Optionnel: pour limiter la largeur maximale */
}

.close-popup {
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 24px;
  cursor: pointer;
}

#form {
  max-width: 400px;
  margin: 0 auto;
}

label {
  display: block;
  margin-top: 15px;
}

input[type="text"],
input[type="email"],
input[type="datetime-local"],
select,
input[type="file"],
textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  box-sizing: border-box;
}

textarea {
  height: 150px;
}

input[type="submit"],
button {
  background-color: #007bff;
  color: #fff;
  border: none;
  cursor: pointer;
  border-radius: 5px;
  margin-left: 3px;
}

input[type="submit"]:hover {
  background-color: #0056b3;
}

.form-page {
  display: none;
}

.form-page.active {
  display: block;
}

/* Styles pour le bouton de pop-up */
.button-container {
  text-align: center;
  margin-top: 20px;
}

.btn,
input[type="submit"] {
  padding: 10px 20px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.btn:hover {
  background-color: #0056b3;
}

/* Médias requête */
@media (prefers-color-scheme: light) {
  :root {
    color: #213547;
    background-color: #ffffff;
  }

  a:hover {
    color: #747bff;
  }

  button {
    background-color: #f9f9f9;
  }
}

@media (max-width: 480px) {
  img {
    width: 100%;
  }
}

@media (max-width: 768px) {
  .icon img {
    width: 280px;
    height: 240px;
  }

  .icon2 img {
    width: 280px;
    height: 240px;
  }
}

@media (max-width: 480px) {
  .icon img {
    width: 200px;
    height: 180px;
  }

  .icon2 img {
    width: 200px;
    height: 180px;
  }
}

/* Déplacement du h2 au-dessus du p */
.m-4 {
  text-align: center;
  font-size: 20px;
}

.fullscreen-div {
  height: 600;
  width: 100%;
  /* Prendre la hauteur de l'écran */
  display: flex;
  justify-content: center;
  align-items: center;
  color: white;
  font-size: 3rem;
  font-family: Arial, sans-serif;
}

.second-div {
  background-color: #ffffff;
  /* Couleur flashy */
}

/*nouveau css */
/* Styles pour le conteneur principal */
.electrician-container {
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #f4f4f4;
  padding: 20px;
  margin-top: 20px;
  margin-bottom: 20px;
}

/* Styles pour le contenu */
.electrician-content {
  display: flex;
  flex-direction: column;
  align-items: center;
}

/* Styles pour l'image */
.electrician-img-content {
  width: 200px;
  /* Ajustez selon la taille de votre image */
  height: 200px;
  /* Ajustez selon la taille de votre image */
  background-color: #ccc;
  /* Couleur de fond de l'image */
  border-radius: 50%;
  /* Rendre l'image ronde */
  overflow: hidden;
  /* Masquer le contenu qui dépasse */
  background-size: cover;
  /* Redimensionner l'image pour remplir la div */
  background-position: center;
  /* Centrer l'image */
}

.electrician-image {
  width: 100%;
  /* Ajustez selon la taille de votre image */
  height: 100%;
  /* Ajustez selon la taille de votre image */
  background-color: transparent;
  /* Supprimer la couleur de fond de l'image */
  background-image: url("/images/Apropos/imgapropos.jpg");
  /* Chemin vers votre image */
  background-size: cover;
  /* Redimensionner l'image pour remplir la div */
  background-position: center;
  /* Centrer l'image */
}

/* Styles pour le texte */
.electrician-text {
  text-align: center;
  margin-top: 20px;
}

/* Styles pour le titre */
.electrician-title {
  font-size: 24px;
  color: #333;
}

/* Styles pour la description */
.electrician-description {
  font-size: 16px;
  color: #666;
  line-height: 1.6;
}

/* Styles pour les petits écrans */
@media (max-width: 768px) {
  .electrician-container {
    flex-direction: column;
    padding: 10px;
  }

  .electrician-content {
    margin-top: 20px;
  }
}

/* Conteneurs */
.container {
  display: flex;
  justify-content: center;
  /* Centrer horizontalement */
  align-items: flex-start;
  /* Aligner en haut */
  flex-wrap: wrap;
  gap: 3vh;
  /* Espacement entre les éléments */
  margin-bottom: 5vh;
}

.icon {
  text-align: center;
  margin: 30px;
  padding: 10px;
  /* Ajoutez une marge autour des icônes pour les séparer */
}

.icon2 {
  text-align: center;
  margin: 30px;
  padding: 10px;
  /* Ajoutez une marge autour des icônes pour les séparer */
}

.icon img {
  width: 5vh;
  height: 5vh;
  display: block;
  margin: 0 auto;
  box-sizing: border-box;
  /* Indique que la taille totale de l'élément inclut le padding et la border */
  /* Ajoute un petit espace entre l'icône et la bordure */
  transition: all 0.3s ease;
}

.icon2 img {
  width: 5vh;
  height: 5vh;
  display: block;
  margin: 0 auto;
  box-sizing: border-box;
  /* Indique que la taille totale de l'élément inclut le padding et la border */
  /* Ajoute un petit espace entre l'icône et la bordure */
  transition: all 0.3s ease;
}

.img-container {
  border-radius: 50px;
  box-shadow: 0 4px 10px #0077ff;
  display: inline-block;
  padding: 15px;
  background-color: white;
}

.img-container2 {
  border-radius: 50px;
  box-shadow: 0 4px 10px #0077ff;
  display: inline-block;
  padding: 15px;
  background-color: white;
}

.description {
  font-size: 25px;
  white-space: nowrap;
}

.img-container:hover {
  transform: translateY(-5px);
  /* Légère translation vers le haut au survol */
}

.img-container2:hover {
  transform: translateY(-5px);
  /* Légère translation vers le haut au survol */
}

.background-image {
  height: 30vh;
  /* Hauteur de 50% de la hauteur de l'écran */
  width: 100%;
  /* Largeur de 100% de la largeur de l'écran */
  /* Chemin de l'image */
  background-size: cover;
  /* Redimensionner l'image pour couvrir toute la div */
  background-position: center;
  /* Centrer l'image horizontalement et verticalement */
}

#carouselExample .carousel-item img {
  width: 100%;
  /* Ajuster l'image à 100% de la largeur du conteneur */
  height: auto;
  /* Ajuster automatiquement la hauteur pour respecter le ratio de l'image */
}

.carousel-item {
  width: 100%;
  /* Largeur ajustée à 100% de l'élément parent */
  height: auto;
  /* Hauteur ajustée automatiquement pour respecter le ratio de l'image */
  overflow: hidden;
  /* Masquer le contenu qui dépasse */
}

.carousel-item img {
  max-width: 100%;
  /* Ajuster la largeur maximale de l'image */
  max-height: 100% !important;
  /* Ajuster la hauteur maximale de l'image */
  display: block !important;
  /* Pour éviter tout espace supplémentaire autour de l'image */
}

.carousel {
  width: 100%;
  /* Largeur ajustée à 100% de l'élément parent */
  max-width: 800px;
  /* Largeur maximale optionnelle */
}

.carousel-control-next.bg-transparent:hover {
  background-color: transparent !important;
}

.carousel-control-next:hover {
  background-color: transparent;
  border-color: transparent;
}

.carousel-control-prev:hover {
  background-color: transparent;
  border-color: transparent;
}

.icon {
  transition: opacity 0.3s;
}

.icon2 {
  transition: opacity 0.3s;
}

.icon.inactive {
  opacity: 0.5;
}

.icon2.inactive {
  opacity: 0.5;
}

.icon.active {
  opacity: 1;
}

.icon2.active {
  opacity: 1;
}

.carousel-indicators [data-bs-target] {
  background-color: #fff;
}

.carousel-indicators .active {
  background-color: #0077ff;
}
</style>

<script>
import { ref, computed } from "vue";
import Datepicker from "vuejs3-datepicker";

window.addEventListener("scroll", function () {
  var footer = document.getElementById("footer");
  var fixedDiv = document.getElementById("fixedDiv");
  var footerRect = footer.getBoundingClientRect();
  var fixedDivHeight = fixedDiv.offsetHeight;

  if (footerRect.top < window.innerHeight) {
    fixedDiv.style.bottom = window.innerHeight - footerRect.top + 20 + "px";
  } else {
    fixedDiv.style.bottom = "20px";
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const icons = document.querySelectorAll(".icon .img-container");

  icons.forEach((icon) => {
    icon.addEventListener("click", () => {
      const parent = icon.parentNode;
      const isActive = parent.classList.contains("active");

      // Reset all icons
      icons.forEach((i) => {
        i.parentNode.classList.remove("active");
        i.parentNode.classList.add("inactive");
      });

      // Toggle the clicked icon
      if (isActive) {
        parent.classList.remove("active");
        parent.classList.remove("inactive");
      } else {
        parent.classList.add("active");
        parent.classList.remove("inactive");
      }

      // Reset opacity if no icon is active
      if (![...icons].some((i) => i.parentNode.classList.contains("active"))) {
        icons.forEach((i) => {
          i.parentNode.classList.remove("inactive");
        });
      }
    });
  });
});
document.addEventListener("DOMContentLoaded", function () {
  const icons = document.querySelectorAll(".icon2 .img-container2");

  icons.forEach((icon) => {
    icon.addEventListener("click", () => {
      const parent = icon.parentNode;
      const isActive = parent.classList.contains("active");

      // Reset all icons
      icons.forEach((i) => {
        i.parentNode.classList.remove("active");
        i.parentNode.classList.add("inactive");
      });

      // Toggle the clicked icon
      if (isActive) {
        parent.classList.remove("active");
        parent.classList.remove("inactive");
      } else {
        parent.classList.add("active");
        parent.classList.remove("inactive");
      }

      // Reset opacity if no icon is active
      if (![...icons].some((i) => i.parentNode.classList.contains("active"))) {
        icons.forEach((i) => {
          i.parentNode.classList.remove("inactive");
        });
      }
    });
  });
});
export default {
  components: {
    Datepicker,
  },
  data() {
    return {
      sending: false,
      successPopupVisible: false,
      errorPopupVisible: false,
      formContactPopupVisible: false,
      currentPage: 1,
      nom: "",
      prenom: "",
      mail: "",
      numero: "",
      type: "",
      description: "",
      sexe: "",
      rue: "",
      ville: "",
      soustype: "",
      dococ: "",
      dococFile: "",
      photos: [],
      dateEntries: [{ date: null, startTime: "", endTime: "" }],
      errors: {
        form: "",
        nom: "",
        prenom: "",
        mail: "",
        numero: "",
        type: "",
        dateEntries: "",
        description: "",
        sexe: "",
        rue: "",
        ville: "",
        soustype: "",
        dococ: "",
        photos: "",
      },
    };
  },
  computed: {
    canAddEntry() {
      const lastEntry = this.dateEntries[this.dateEntries.length - 1];
      return lastEntry.date && lastEntry.startTime && lastEntry.endTime;
    },
    disabledDates() {
      const today = new Date();
      const next5Days = new Set();
      for (let i = 0; i < 5; i++) {
        const date = new Date(today);
        date.setDate(today.getDate() + i + 1);
        next5Days.add(date.toDateString());
      }
      return {
        customPredictor(date) {
          const day = date.getDay();
          // Disable weekends (Saturday: 6, Sunday: 0) and the next 5 days
          return (
            day === 0 ||
            day === 6 ||
            date < today ||
            next5Days.has(date.toDateString())
          );
        },
      };
    },
  },
  methods: {
    submitForm() {
      if (this.photos.length > 5) {
        this.showErrorMessage("5 fichiers maximum");
        return;
      }
      let datestimes = JSON.stringify(this.dateEntries);
      // Créer un objet FormData pour envoyer les données du formulaire
      const formData = new FormData();
      formData.append("nom", this.nom);
      formData.append("prenom", this.prenom);
      formData.append("mail", this.mail);
      formData.append("numero", this.numero);
      formData.append("type", this.type);
      formData.append("description", this.description);
      formData.append("sexe", this.sexe);
      formData.append("rue", this.rue);
      formData.append("ville", this.ville);
      formData.append("soustype", this.soustype);
      formData.append("dococFile", this.dococFile);
      formData.append("datestimes", datestimes);
      this.photos.forEach((photo, index) => {
        formData.append(`photos[]`, photo);
      });

      this.sending = true;
      // Envoyer les données du formulaire au fichier PHP via la méthode fetch
      fetch(`${import.meta.env.VITE_BACKEND_URL}/form.php`, {
        method: "POST",
        body: formData,
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error("Network response was not ok");
          }
          return response.text();
        })
        .then((data) => {
          let res = JSON.parse(data) || data;
          //console.log(data); // Afficher la réponse du serveur

          if (res.status == "success") {
            // Réinitialiser les champs de formulaire après la soumission réussie
            this.nom = "";
            this.prenom = "";
            this.mail = "";
            this.numero = "";
            this.type = "";
            this.description = "";
            this.sexe = "";
            this.rue = "";
            this.ville = "";
            this.soustype = "";
            this.photos = [];

            this.hideFormContactPopup();
            this.currentPage = 1;

            this.showSuccessPopup();
          } else if (res.status == "fail") {
            this.showErrorMessage(res.data.message);
          } else {
            this.showErrorMessage("Erreur inconnue");
          }

          this.sending = false;
        })
        .catch((error) => {
          this.showErrorMessage(
            "Erreur lors de la soumission du formulaire: " + error
          );
        });
    },
    handleDococFileChange(event) {
      this.dococFile = event.target.files[0];
    },
    handleFileChange(event) {
      // Mettre à jour la valeur de la photo lorsqu'une photo est sélectionnée
      this.photos = Array.from(event.target.files);
    },
    showErrorMessage(message) {
      this.errors.form = message;
      this.showErrorPopup();
    },
    showFormContactPopup() {
      this.formContactPopupVisible = true;
    },
    hideFormContactPopup() {
      this.formContactPopupVisible = false;
      Object.keys(this.errors).forEach((key) => {
        this.errors[key] = "";
      });
    },
    showSuccessPopup() {
      this.successPopupVisible = true;
    },
    closeSuccessPopup() {
      this.successPopupVisible = false;
    },
    showErrorPopup() {
      this.errorPopupVisible = true;
    },
    closeErrorPopup() {
      this.errorPopupVisible = false;
    },
    nextPage() {
      if (this.currentPage == 1) {
        if (this.validatePage1()) {
          this.currentPage++;
        }
      } else if (this.currentPage == 2) {
        if (this.validatePage2()) {
          this.currentPage++;
        }
      } else if (this.currentPage == 3) {
        if (this.validatePage3()) {
          this.submitForm();
        }
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    validatePage1() {
      this.errors.sexe = "";
      this.errors.nom = "";
      this.errors.prenom = "";
      this.errors.mail = "";
      this.errors.numero = "";
      let valid = true;

      // Validation du sexe
      if (this.sexe == "") {
        this.errors.sexe = "Veuillez selectionner votre sexe.";
        valid = false;
      }

      const nomPattern = /^[a-zA-ZÀ-ÿ\s-]{2,}$/;
      if (!nomPattern.test(this.nom.trim())) {
        this.errors.nom =
          "Veuillez entrer un nom valide (au moins 2 lettres, sans chiffres ni caractères spéciaux).";
        valid = false;
      }

      // Validation du prénom
      const prenomPattern = /^[a-zA-ZÀ-ÿ\s-]{2,}$/;
      if (!prenomPattern.test(this.prenom)) {
        this.errors.prenom =
          "Veuillez entrer un prénom valide (au moins 2 lettres, sans chiffres ni caractères spéciaux).";
        valid = false;
      }

      // Validation de l'email
      const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
      if (!emailPattern.test(this.mail)) {
        this.errors.mail = "Veuillez entrer une adresse email valide.";
        valid = false;
      }

      // Validation du numéro de téléphone
      const numeroPattern = /^(0[0-9]{9}|\+32[0-9]{9})$/;
      const cleanedPhone = this.numero.replace(/\s+/g, "");
      if (!numeroPattern.test(cleanedPhone)) {
        this.errors.numero = "Veuillez entrer un numéro de téléphone valide.";
        valid = false;
      }

      return valid;
    },

    validatePage2() {
      this.errors.type = "";
      this.errors.soustype = "";
      this.errors.date = "";
      this.errors.dateEntries = "";
      this.errors.description = "";
      let valid = true;

      // Validation du type
      if (this.type == "") {
        this.errors.type = "Veuillez selectionner le type d'intervention.";
        valid = false;
      }

      // Validation du sous type
      if (["2", "3", "4"].includes(this.type) && this.soustype == "") {
        this.errors.soustype =
          "Veuillez selectionner le sous-type d'intervention.";
        valid = false;
      }

      // Validation de la date d'intervention
      if (!this.canAddEntry) {
        this.errors.dateEntries =
          "Veuillez selectionner la / les date(s) d'intervention. ";
        valid = false;
      }

      // Validation de la description
      if (this.description.length > 1000) {
        this.errors.description =
          "La description ne peut pas faire plus de 1000 caractères.";
        valid = false;
      }

      return valid;
    },

    validatePage3() {
      this.errors.rue = "";
      this.errors.ville = "";
      this.errors.dococ = "";
      this.errors.description = "";
      let valid = true;

      // Validation du type
      if (this.rue == "") {
        this.errors.rue = "Veuillez indiquer la rue et le numéro.";
        valid = false;
      }

      // Validation du sous type
      if (this.ville == "") {
        this.errors.ville = "Veuillez indiquer la ville et le code postal.";
        valid = false;
      }

      // Validation de la dococ
      if (this.dococ == "true" && this.dococFile == null) {
        this.errors.dococ =
          "Veuillez ajouter un document d'organisme de contrôle.";
        valid = false;
      }

      return valid;
    },

    // Fonctions pour les dates et heures //

    addEntry() {
      if (this.canAddEntry && this.dateEntries.length < 5) {
        this.dateEntries.push({ date: null, startTime: "", endTime: "" });
      }
    },
    removeEntry(index) {
      this.dateEntries.splice(index, 1);
    },
    validateStartTime(index) {
      const entry = this.dateEntries[index];
      if (entry.startTime < "08:00") {
        entry.startTime = "08:00";
      }
      if (entry.startTime > "15:59") {
        entry.startTime = "15:59";
      }
      if (entry.startTime >= entry.endTime) {
        entry.endTime = this.getEndTime(entry.startTime);
      }
    },
    validateEndTime(index) {
      const entry = this.dateEntries[index];
      if (entry.endTime < "08:01") {
        entry.endTime = "08:01";
      }
      if (entry.endTime > "16:00") {
        entry.endTime = "16:00";
      }
      if (entry.endTime <= entry.startTime) {
        entry.startTime = this.getStartTime(entry.endTime);
      }
    },
    getEndTime(startTime) {
      let [hours, minutes] = startTime.split(":").map(Number);
      if (hours === 15 && minutes >= 59) {
        return "16:00";
      }
      minutes += 30;
      if (minutes >= 60) {
        hours += 1;
        minutes = 0;
      }
      return `${hours.toString().padStart(2, "0")}:${minutes
        .toString()
        .padStart(2, "0")}`;
    },
    getStartTime(endTime) {
      let [hours, minutes] = endTime.split(":").map(Number);
      if (hours === 8 && minutes <= 1) {
        return "08:00";
      }
      minutes -= 30;
      if (minutes < 0) {
        hours -= 1;
        minutes = 59;
      }
      return `${hours.toString().padStart(2, "0")}:${minutes
        .toString()
        .padStart(2, "0")}`;
    },
  },
};
</script>
