# moodle-local_gitplugins

This plugin allows admins to install plugins directly from Git repositories. At the moment, it allows a user to paste in the URL of a Git repository, and will then clone the repo to the tempdir, move it to the correct installation directory in the webroot, and then redirect to /admin/index.php for install.
