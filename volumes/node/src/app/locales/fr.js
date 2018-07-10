/*
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

export default {
    global: {
        menu: {
            title: 'Titre',
            profil: 'Profile',
            logout: 'Se déconnecter',
            dashboard: 'Dashboard',
            admin: {
                users: 'Utilisateurs'
            },
        }
    },
    dashboard: {
        'title': 'Dashboard',
        'content': "Personnalisez votre dashboard !",
    },
    login: {
        "title": "Connexion",
        'username': "Nom d'utilisateur",
        'password': "Mot de passe",
        'submit': "Se connecter",
        'create_account': "Pas encore inscrit ? Créer un compte maintenant !",
        'error': {
            'username_or_password': "Mauvais nom d'utilisateur / mot de passe."
        }
    },
    profil: {
        'title': 'Profile',
        'imgProfilUpload': 'Télécharger une nouvelle image de profil',
        'username': "Nom d'utilisateur",
        'email': "Adresse email",
    },
    register: {
        'title': 'Inscription',
        'username': "Nom d'utilisateur",
        'email': "Adresse email",
        'password': "Mot de passe",
        'passwordConfirm': "Mot de passe (confirmation)",
        'termsAccepted': "J'accepte les conditions générales d'utilisation",
        'submit': "S'inscrire",
        'login': "Se connecter",
    },
    admin_user: {
        title: 'Administration des utilisateurs',
        no_data: 'Pas d\'utilisateurs trouvés.',
        id: 'ID',
        username: 'Nom d\'utilisateur',
        email: 'Adresse mail',
        registratedAt: 'Inscrit le',
        is_active: 'Actif ?',
        page: {
            first: 'Première page',
            previous: 'Page précédente',
            next: 'Page Suivante',
            last: 'Dernière page',
        }
    }
}