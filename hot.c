/**
 * MySQL/MariaDB Database Setup Program
 * 
 * This program connects to MySQL/MariaDB, creates a database called 'hot',
 * and sets up tables for bookings, images, rooms, and users.
 * 
 * Compile with:
 * gcc -o setup_db setup_db.c $(mysql_config --cflags --libs)
 */

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <termios.h>
#include <mysql/mysql.h>

// SQL statements for creating tables
const char* create_database = "CREATE DATABASE IF NOT EXISTS hot";
const char* use_database = "USE hot";

const char* create_tables[] = {
    // Create rooms table first (referenced by other tables)
    "CREATE TABLE IF NOT EXISTS `rooms` ("
    "  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,"
    "  `room_name` varchar(255) NOT NULL,"
    "  `desk_total` int(11) DEFAULT NULL,"
    "  `created_at` timestamp NULL DEFAULT NULL,"
    "  `updated_at` timestamp NULL DEFAULT NULL,"
    "  `room_image` varchar(255) DEFAULT NULL,"
    "  PRIMARY KEY (`id`)"
    ") ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Create users table
    "CREATE TABLE IF NOT EXISTS `users` ("
    "  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,"
    "  `name` varchar(255) DEFAULT NULL,"
    "  `email` varchar(255) NOT NULL,"
    "  `email_verified_at` timestamp NULL DEFAULT NULL,"
    "  `password` varchar(255) NOT NULL,"
    "  `two_factor_secret` text DEFAULT NULL,"
    "  `two_factor_recovery_codes` text DEFAULT NULL,"
    "  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,"
    "  `remember_token` varchar(100) DEFAULT NULL,"
    "  `current_team_id` bigint(20) unsigned DEFAULT NULL,"
    "  `profile_photo_path` varchar(2048) DEFAULT NULL,"
    "  `created_at` timestamp NULL DEFAULT NULL,"
    "  `updated_at` timestamp NULL DEFAULT NULL,"
    "  `guid` varchar(255) DEFAULT NULL,"
    "  `domain` varchar(255) DEFAULT NULL,"
    "  `is_admin` tinyint(1) DEFAULT NULL,"
    "  PRIMARY KEY (`id`),"
    "  UNIQUE KEY `users_email_unique` (`email`),"
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

int main(int argc, char *argv[]) {
    MYSQL *conn;
    int i;
    int error = 0;
    
    // Connection parameters
    char *server = "localhost";
    char *user = "root";
    char password[256];  // Buffer to store password
    
    // Ask for password using more portable method
    printf("Enter MySQL root password: ");
    
    // Disable terminal echo for password entry
    struct termios old_term, new_term;
    tcgetattr(STDIN_FILENO, &old_term);
    new_term = old_term;
    new_term.c_lflag &= ~(ECHO);
    tcsetattr(STDIN_FILENO, TCSANOW, &new_term);
    
    // Get password
    if (fgets(password, sizeof(password), stdin) == NULL) {
        tcsetattr(STDIN_FILENO, TCSANOW, &old_term);
        fprintf(stderr, "Error reading password\n");
        return 1;
    }
    
    // Restore terminal settings
    tcsetattr(STDIN_FILENO, TCSANOW, &old_term);
    printf("\n");
    
    // Remove newline character if present
    size_t len = strlen(password);
    if (len > 0 && password[len-1] == '\n')
        password[len-1] = '\0';
    
    // Initialize the MySQL client library
    if (mysql_library_init(0, NULL, NULL)) {
        fprintf(stderr, "Could not initialize MySQL library\n");
        return 1;
    }
    
    // Initialize connection handler
    conn = mysql_init(NULL);
    if (conn == NULL) {
        fprintf(stderr, "mysql_init() failed\n");
        return 1;
    }
    
    // Connect to database server (without specifying database)
    if (mysql_real_connect(conn, server, user, password, NULL, 0, NULL, 0) == NULL) {
        fprintf(stderr, "Connection error: %s\n", mysql_error(conn));
        mysql_close(conn);
        return 1;
    }
    
    printf("Connected successfully to MySQL server.\n");

    // Create database
    if (execute_query(conn, create_database)) {
        mysql_close(conn);
        mysql_library_end();
        return 1;
    }
    
    // Switch to the 'hot' database
    if (execute_query(conn, use_database)) {
        mysql_close(conn);
        mysql_library_end();
        return 1;
    }

    // Create tables in order (handling dependencies)
    for (i = 0; i < sizeof(create_tables)/sizeof(create_tables[0]); i++) {
        if (execute_query(conn, create_tables[i])) {
            error = 1;
            break;
        }
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
