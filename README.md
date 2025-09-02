# Arknights Toolkit

This project was part of COS30043 - Interface Design and Development. It is a Single-Page Application (SPA) that provides drop rate information; allows users to make, view, and like posts; and provides game news.

The project was more focused on the front-end aspect, but became more full-stack due to the need for a database and APIs for better persistent data handling. So, some back-end features such as admin roles and password encryption are not yet fully implemented.

I currently do not have plans to host this website publicly.

## Requirements

You will need some version of Node.js if planning to run locally. However, you will not be able to use database features unless you define a proxy in [`vite.config.js`](vite.config.js)

The version of Node.js used in this project is **22.13.0**

Otherwise, any sort of LAMP stack or its variations can be used. 

## Installation

1. Clone the project and
install dependencies:

```bash
git clone https://github.com/jjv7/arknights-toolkit.git
cd arknights-toolkit
npm install
```

2. Set up MySQL/MariaDB:

    - Create a dedicated user account and database
    - Use the [`setup.sql`](apis/setup.sql) script to setup the tables in the database with placeholder data
    - Update the database credentials (host, username, password, db name) inside the PHP API files:
        - [`api_posts.php`](apis/api_posts.php)
        - [`api_signup.php`](apis/api_signup.php)
        - [`api_user.php`](apis/api_user.php)

3. Point all corresponding API references to where they are deployed:
    - api_user.php in [`Login.vue`](src/views/Login.vue)
    - api_signup.php in [`SignUp.vue`](src/views/SignUp.vue)
    - api_posts.php in [`CreatePost.vue`](src/views/CreatePost.vue)
    - api_posts.php in [`EditPost.vue`](src/views/EditPost.vue)
    - api_posts.php in [`PostPage.vue`](src/views/PostPage.vue)
    - api_posts.php in [`PostSearch.vue`](src/views/PostSearch.vue)

## Using the Development Server

1. Run the below command
```bash
npm run dev
```
2. Open your browser and navigate to [`http://localhost:5173`](http://localhost:5173)


## Building for Production

```bash
npm run build
```

## Planned Features

I am working on these and will implement them when I have the time:
- .env file support for APIs and database details
- Proper admin support in the backend
- Password encryption
- Static pages

## Disclaimer

Arknights Toolkit is not affiliated with, endorsed by, or associated with Hypergryph, Studio Montagne, or Yostar. All trademarks and content belong to their respective owners.

## Licence

This project is licenced under the MIT License. See the [LICENCE](LICENSE) file for details.