/**
 * MySQL/MariaDB Database Setup Program
 * 
 * This program connects to MySQL/MariaDB, creates a database called 'hot',
 * and sets up tables for bookings, images, rooms, and users.
 * 
 * ensure you have installed all libraries such as mysql and libsoium
 * sudo apt install libsodium-dev
 * 
 * Compile with:
 * gcc -o hot_dbinit hot.c $(mysql_config --cflags --libs) -lsodium
 */

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <termios.h>
#include <mysql/mysql.h>
#include <sodium.h>

// SQL statements for creating tables
const char* create_database = "CREATE DATABASE IF NOT EXISTS hot";
const char* use_database = "USE hot";

const char* create_tables[] = {
    // Create rooms table first (referenced by other tables)
    "CREATE TABLE IF NOT EXISTS `rooms` ("
    "  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,"
    "  `room_name` varchar(255) NOT NULL,"
    "  `desk_total` int(11) DEFAULT NULL,"
    "  `created_at` timestamp NULL DEFAULT current_timestamp(),"
    "  `updated_at` timestamp NULL DEFAULT NULL,"
    "  `room_image` varchar(255) DEFAULT NULL,"
    "  PRIMARY KEY (`id`)"
    ") ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Create users table
    "CREATE TABLE IF NOT EXISTS `users` ("
    "  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,"
    "  `name` varchar(255) DEFAULT NULL,"
    "  `username` varchar(255) NOT NULL,"
    "  `username_verified_at` timestamp NULL DEFAULT current_timestamp(),"
    "  `password` varchar(255) NOT NULL,"
    "  `two_factor_secret` text DEFAULT NULL,"
    "  `two_factor_recovery_codes` text DEFAULT NULL,"
    "  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,"
    "  `remember_token` varchar(100) DEFAULT NULL,"
    "  `current_team_id` bigint(20) unsigned DEFAULT NULL,"
    "  `profile_photo_path` varchar(2048) DEFAULT NULL,"
    "  `created_at` timestamp NULL DEFAULT current_timestamp(),"
    "  `updated_at` timestamp NULL DEFAULT NULL,"
    "  `guid` varchar(255) DEFAULT NULL,"
    "  `domain` varchar(255) DEFAULT NULL,"
    "  `is_admin` tinyint(1) DEFAULT NULL,"
    "  PRIMARY KEY (`id`),"
    "  UNIQUE KEY `users_username_unique` (`username`),"
    "  UNIQUE KEY `users_guid_unique` (`guid`)"
    ") ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Create bookings table (with desk_id but no foreign key constraint)
    "CREATE TABLE IF NOT EXISTS `bookings` ("
    "  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,"
    "  `user_id` bigint(20) unsigned DEFAULT NULL,"
    "  `desk_id` bigint(20) unsigned DEFAULT NULL,"
    "  `room_id` bigint(20) DEFAULT NULL,"
    "  `res_date` bigint(20) DEFAULT NULL,"
    "  `updated_at` timestamp NULL DEFAULT NULL,"
    "  `created_at` timestamp NULL DEFAULT current_timestamp(),"
    "  `user_name` varchar(255) DEFAULT NULL,"
    "  UNIQUE KEY `bookings_id_idx` (`id`) USING BTREE,"
    "  KEY `bookings_user_id_foreign` (`user_id`),"
    "  KEY `bookings_desk_id` (`desk_id`),"
    "  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`)"
    "  REFERENCES `users` (`id`) ON DELETE CASCADE"
    ") ENGINE=InnoDB AUTO_INCREMENT=325 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Create images table
    "CREATE TABLE IF NOT EXISTS `images` ("
    "  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,"
    "  `room_id` bigint(20) unsigned DEFAULT NULL,"
    "  `image_path` varchar(255) DEFAULT NULL,"
    "  UNIQUE KEY `images_id_idx` (`id`) USING BTREE,"
    "  KEY `images_room_id_foreign` (`room_id`) USING BTREE,"
    "  CONSTRAINT `images_ibfk_1` FOREIGN KEY (`room_id`)"
    "  REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE"
    ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
};

// Function to execute a SQL query and handle errors
int execute_query(MYSQL *conn, const char *query) {
    if (mysql_query(conn, query)) {
        fprintf(stderr, "Query error: %s\n", mysql_error(conn));
        return 1;
    }
    printf("Successfully executed task\n");
    return 0;
}

void print_help() {
    printf("Usage: hot_dbinit [server] [username]\n");
    printf("\nOptions:\n");
    printf("  server    - MySQL/MariaDB server hostname (default: localhost)\n");
    printf("  username  - MySQL/MariaDB username (default: root)\n");
    printf("\nExample:\n");
    printf("  ./hot_dbinit myserver.com myuser\n\n");
}


int main(int argc, char *argv[]) {
    MYSQL *conn;
    int i;
    int error = 0;

    // Connection parameters
    char server[256] = "localhost"; // Default hostname
    char user[256] = "root";        // Default username
    char password[256];             // Buffer to store password

    // Check for help argument
    if (argc == 2 && (strcmp(argv[1], "-h") == 0 || strcmp(argv[1], "--help") == 0)) {
        print_help();
        return 0;
    }

    // Check if hostname and username were provided
    if (argc >= 3) {
        strncpy(server, argv[1], sizeof(server) - 1);
        strncpy(user, argv[2], sizeof(user) - 1);
    }

    printf("Connecting to MySQL server '%s' with user '%s'...\n", server, user);

    // Ask for password
    printf("Enter MySQL password for %s@%s: ", user, server);
    
    // Disable terminal echo for password input
    struct termios old_term, new_term;
    tcgetattr(STDIN_FILENO, &old_term);
    new_term = old_term;
    new_term.c_lflag &= ~(ECHO);
    tcsetattr(STDIN_FILENO, TCSANOW, &new_term);
    
    // Read password
    if (fgets(password, sizeof(password), stdin) == NULL) {
        tcsetattr(STDIN_FILENO, TCSANOW, &old_term);
        fprintf(stderr, "Error reading password\n");
        return 1;
    }
    
    // Restore terminal settings
    tcsetattr(STDIN_FILENO, TCSANOW, &old_term);
    printf("\n");

    // Remove newline from password
    size_t len = strlen(password);
    if (len > 0 && password[len - 1] == '\n') {
        password[len - 1] = '\0';
    }

    // Initialize MySQL library
    if (mysql_library_init(0, NULL, NULL)) {
        fprintf(stderr, "Could not initialize MySQL library\n");
        return 1;
    }

    // Initialize connection
    conn = mysql_init(NULL);
    if (conn == NULL) {
        fprintf(stderr, "mysql_init() failed\n");
        return 1;
    }

    // Connect to database
    if (mysql_real_connect(conn, server, user, password, NULL, 0, NULL, 0) == NULL) {
        fprintf(stderr, "Connection error: %s\n", mysql_error(conn));
        mysql_close(conn);
        return 1;
    }

    printf("Connected successfully to MySQL server.\n");

    // Execute database setup
    if (execute_query(conn, create_database) || execute_query(conn, use_database)) {
        mysql_close(conn);
        mysql_library_end();
        return 1;
    }

    // Create tables
    for (i = 0; i < sizeof(create_tables) / sizeof(create_tables[0]); i++) {
        if (execute_query(conn, create_tables[i])) {
            error = 1;
            break;
        }
    }

    // Admin credentials
    char admin_name[] = "admin";
    char admin_username[] = "admin";
    char admin_password[] = "pavlova";
    char hashed_password[crypto_pwhash_STRBYTES];

    // Hash password with bcrypt-equivalent hashing
    if (crypto_pwhash_str(hashed_password, admin_password, strlen(admin_password), 
        crypto_pwhash_OPSLIMIT_MODERATE, crypto_pwhash_MEMLIMIT_MODERATE) != 0) {
        fprintf(stderr, "Password hashing failed\n");
        return 1;
    }

    // Construct SQL query
    char query[512];
    snprintf(query, sizeof(query),
             "INSERT INTO users (name, username, password, is_admin) VALUES ('%s', '%s', '%s', 1)",
             admin_name, admin_username, hashed_password);

    // Execute query
    if (execute_query(conn, query)) {
        fprintf(stderr, "Failed to create admin user\n");
        return 1;
    }

    printf("Admin user created successfully!\n");



    // Insert the first room record
    const char *insert_room = "INSERT INTO rooms (room_name) VALUES ('...')";
    if (execute_query(conn, insert_room)) {
        error = 1;
    }

    // Clean up
    mysql_close(conn);
    mysql_library_end();

    if (error) {
        printf("Database setup failed.\n");
        return 1;
    }

    printf("\nDatabase 'hot' and all required tables have been created successfully!\n");
    return 0;
}
