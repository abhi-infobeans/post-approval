==== Post Approval === 
Requires at least: 6.4.1  
Requires WordPress at least: 6.4.1  
Tested on: 6.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

## Description

The Post Approval plugin is a powerful tool for managing post restrictions and ensuring a controlled review process before posts are published. It allows administrators to assign post types, such as posts and custom posts, to specific editor users for careful review.

### Features

- **Post Approval Menu:** Restricts access to the post list for admin users.
- **Restriction Settings Form:** Admin users can configure post restriction settings, including adding, editing, and deleting restrictions.
  - Set any post type (post, custom-post) to multiple editor user roles for restriction.
  - After setting restrictions, newly created posts will be automatically assigned to restricted editor users for review before publishing.

- **Pending Review Post Menu:** Provides editor users with a convenient list of pending posts assigned for review.
  - Editors can review, publish, and reassign posts to other editor users, ensuring a collaborative and streamlined review process.

- **Fair Assignment:** The plugin manages the life cycle of restricted posts. When a user submits a post, the plugin intelligently assigns the post to editors, keeping it in draft status until any assigned editor approves it. This ensures fairness by evenly distributing submitted posts among editors.

- **Customizable Workflow:** Tailor the plugin to fit your workflow seamlessly. Customize post types, editor roles, and other settings through the intuitive plugin interface.

## Installation

1. Upload the `post-approval` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to the "POST APPROVAL" menu.

## Getting Started

1. Go to the "POST APPROVAL" menu and configure your post restriction settings using the "Restriction Settings Form."
2. Create new posts, and they will automatically be assigned to the designated editor users for review.
3. Editors can find pending review posts in the "PENDING REVIEW POST" menu, review them, publish, and reassign as needed.

## Frequently Asked Questions

### How are posts assigned to editors?

Posts are intelligently assigned to editors to ensure a fair distribution of review tasks.

### Where can editors find pending review posts?

Editors can find pending review posts in the "MY PENDING REVIEW POST" menu.

## Changelog

### 1.0.0
- Initial release.

## Contribute

You can contribute to the development of this plugin on [GitHub](https://github.com/post-approval).

## License

This plugin is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.
