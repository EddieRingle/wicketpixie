<?php
/**
 * WicketPixie
 * (c) 2006-2011 Eddie Ringle <eddie@eringle.net>
 * Provided by Chris Pirillo <chris@pirillo.com>
 *
 * Licensed under the New BSD License.
 */

class SourceManager
{
    static function install()
    {
        global $wpdb;

        $table_revision = '1.0';

        $table_name = $wpdb->prefix . 'wipi_sources';

        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name
                || get_option('wipi_source_manager_revision') != $table_revision) {
            $sql = "CREATE TABLE " . $table_name . " (
                    id int NOT NULL AUTO_INCREMENT,
                    title varchar(255) NOT NULL,
                    profile_url varchar(255) NOT NULL,
                    feed_url varchar(255) NOT NULL,
                    lifestream boolean NOT NULL,
                    updates boolean NOT NULL,
                    favicon varchar(255) NOT NULL,
                    UNIQUE KEY id (id)
                    );";
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql);
            update_option('wipi_source_manager_revision', $table_revision);
        }
    }

    static function collect_sources()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wipi_sources';
        $sources = $wpdb->get_results("SELECT * FROM $table_name");
        if (is_array($sources)) {
            return $sources;
        } else {
            return array();
        }
    }

    static function collect_stream_sources()
    {
        global $wpdb;

        $table = $wpdb->prefix . 'wipi_sources';
        $streams = $wpdb->get_results("SELECT * FROM $table WHERE lifestream = 1");

        if (is_array($streams)) {
            return $streams;
        } else {
            return array();
        }
    }

    static function fetch_source($id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wipi_sources';
        $source = $wpdb->get_row("SELECT * FROM $table WHERE id = $id");
        return $source;
    }

    static function add($req)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wipi_sources';
        if (isset($req['title']) && isset($req['profile_url']) && $req['title'] && $req['profile_url']) {
            if ((!isset($req['feed_url']) || !$req['feed_url']) && (isset($req['lifestream']) || isset($req['updates']))) {
                return -3;
            }
            if (!$wpdb->get_var("SELECT id FROM $table WHERE feed_url = '" . $req['feed_url'] . "'")) {
                preg_match('@^(?:http://)?([^/]+)@i', $req['profile_url'], $matches);
                $favicon_domain = $matches[1];
                $sql = $wpdb->prepare("INSERT INTO " . $table . " (id, title, profile_url, feed_url, lifestream, updates, favicon) VALUES('', '%s', '%s', '%s', '%d', '%d', '%s')",
                        $req['title'], $req['profile_url'], $req['feed_url'],
                        (isset($req['lifestream'])) ? 1 : 0, (isset($req['updates'])) ? 1 : 0,
                        'http://www.google.com/s2/favicons?domain=' . $favicon_domain);
                $wpdb->query($sql);
                return 0;
            } else {
                return -2;
            }
        } else {
            return -1;
        }
    }

    static function edit($req)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wipi_sources';
        if (isset($req['title']) && isset($req['profile_url']) && $req['title'] && $req['profile_url']) {
            if ((!isset($req['feed_url']) || !$req['feed_url']) && (isset($req['lifestream']) || isset($req['updates']))) {
                return -2;
            }
            preg_match('@^(?:http://)?([^/]+)@i', $req['profile_url'], $matches);
            $favicon_domain = $matches[1];
            $sql = $wpdb->prepare("UPDATE $table SET title='%s', profile_url='%s', feed_url='%s', lifestream=%d, updates=%d, favicon='%s' WHERE id=%d",
                    $req['title'], $req['profile_url'], $req['feed_url'],
                    (isset($req['lifestream'])) ? 1 : 0, (isset($req['updates'])) ? 1 : 0,
                    'http://www.google.com/s2/favicons?domain=' . $favicon_domain, $req['id']);
            $wpdb->query($sql);
            return 0;
        } else {
            return -1;
        }
    }

    static function delete($id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wipi_sources';
        $sql = $wpdb->prepare("DELETE FROM $table WHERE id = %d", $id);
        $wpdb->query($sql);
        return 0;
    }
}

function wipi_admin_render_source_manager()
{
    if (isset($_REQUEST['action'])) {
        check_admin_referer('wicketpixie');
        switch ($_REQUEST['action']) {
            case 'add':
            $addResult = SourceManager::add($_REQUEST);
            if ($addResult == 0) {
                $message = __('Source saved successfully.');
            } else if ($addResult == -1) {
                $error = __('Missing Source title or Source profile URL, Source not saved.');
            } else if ($addResult == -2) {
                $error = __('A source with that feed URL already exists, Source not saved.');
            } else if ($addResult == -3) {
                $error = __('You cannot enable Lifestream or Status Update aggregation for Sources without feed URLs, Source not saved.');
            }
            break;
            case 'edit':
            $editResult = SourceManager::edit($_REQUEST);
            if ($editResult == 0) {
                $message = __('Source modified successfully.');
            } else if ($editResult == -1) {
                $error = __('Missing Source title or Source profile URL, Source not updated.');
            } else if ($editResult == -2) {
                $error = __('You cannot enable Lifestream or Status Update aggregation for Sources without feed URLs, Source not updated.');
            }
            break;
            case 'delete':
            $deleteResult = SourceManager::delete($_REQUEST['id']);
            if ($deleteResult == 0) {
                $message = __('Source deleted successfully.');
            }
            break;
            default:
            break;
        }
    }
?>
    <?php
        if (isset($message)) {
    ?>
        <div id="message" class="error fade" style="background-color: #ebffe8; border-color: #0c0;"><p><strong><?php echo $message; ?></strong></p></div>
    <?php
        }
        if (isset($error)) {
    ?>
        <div id="message" class="fade error"><p><strong><?php echo $error; ?></strong></p></div>
    <?php
        }
    ?>
    <div class="wrap">
        <div id="admin-options">
            <h2>Source Manager</h2>
            <p>Manage all of your social stream sources here.</p>
            <h3>Source Listing</h3>
            <?php
            $sources = SourceManager::collect_sources();
            if (!empty($sources)) {
            ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=wipi_source_manager" method="post">
                <table class="form-table" style="margin-bottom:20px;width:670px;">
                    <tr>
                        <th style="width: 16px;">Icon</th>
                        <th style="text-align: center;">Name</th>
                        <th style="width: 14px; text-align: center;">Feed</th>
                        <th style="width: 24px; text-align: center;">Lifestream</th>
                        <th style="width: 20px; text-align: center;">Status</th>
                        <th style="width: auto; text-align: center;">Actions</th>
                    </tr>
                <?php
                wp_nonce_field('wicketpixie');
                foreach ($sources as $source) {
                ?>
                    <tr>
                        <td style="text-align: center;"><img src="<?php echo $source->favicon; ?>" alt="" /></td>
                        <td style="text-align: center;"><a href="<?php echo $source->profile_url; ?>"><?php echo $source->title; ?></a></td>
                        <td style="text-align: center;">
                            <?php if (!empty($source->feed_url)) { ?>
                                <a href="<?php echo $source->feed_url; ?>"><img src="<?php wipi_template_uri(); ?>/images/icon-feed.gif" alt="Feed" /></a>
                                <?php } ?>
                            </td>
                            <td style="text-align: center;">
                                <?php echo ($source->lifestream == 0) ? 'No' : 'Yes'; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php echo ($source->updates == 0) ? 'No' : 'Yes'; ?>
                            </td>
                            <td style="text-align: center;">
                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=wipi_source_manager" style="display: inline;">
                                    <?php wp_nonce_field('wicketpixie'); ?>
                                    <input type="submit" value="Edit" />
                                    <input type="hidden" name="id" value="<?php echo $source->id; ?>" />
                                    <input type="hidden" name="action" value="edit-form" />
                                </form>
                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=wipi_source_manager" style="display: inline;">
                                    <?php wp_nonce_field('wicketpixie'); ?>
                                    <input type="submit" value="Delete" />
                                    <input type="hidden" name="id" value="<?php echo $source->id; ?>" />
                                    <input type="hidden" name="action" value="delete" />
                                </form>
                            </td>
                        </tr>
            <?php
                }
            ?>
                    </table>
            <?php
            } else {
            ?>
                <p>No sources found! Why don't you add one?</p>
            <?php
            }
            ?>
        </form>
        <?php if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'edit-form' || ($_REQUEST['action'] == 'edit' && isset($error)))) {
            $source = SourceManager::fetch_source($_REQUEST['id']); ?>
            <h3>Edit Source</h3>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=wipi_source_manager" method="post">
                <?php wp_nonce_field('wicketpixie'); ?>
                <label for="source-name">Name:</label><br />
                <input type="text" id="source-name" name="title" value="<?php echo $source->title; ?>"/><br />
                <label for="source-profile">Profile URL:</label><br />
                <input type="text" id="source-profile" name="profile_url" value="<?php echo $source->profile_url; ?>"/><br />
                <label for="source-feed">Feed URL:</label><br />
                <input type="text" id="source-feed" name="feed_url" value="<?php echo $source->feed_url; ?>"/><br />
                <input type="checkbox" id="source-lifestream" name="lifestream" <?php if ($source->lifestream == 1) echo 'checked'; ?>/>
                <label for="source-lifestream">Add to Lifestream</label><br />
                <input type="checkbox" id="source-status" name="updates" <?php if ($source->updates == 1) echo 'checked'; ?>/>
                <label for="source-status">Use in Status Box</label><br />
                <input type="submit" value="Edit Source" />
                <input type="hidden" name="action" value="edit" />
                <input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>" />
            </form>
            <?php } ?>
            <h3>Add a Source</h3>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=wipi_source_manager" method="post">
                <?php wp_nonce_field('wicketpixie'); ?>
                <label for="source-name">Name:</label><br />
                <input type="text" id="source-name" name="title" value="<?php echo (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add' && isset($_POST['title'])) ? $_POST['title'] : ''; ?>" /><br />
                <label for="source-profile">Profile URL:</label><br />
                <input type="text" id="source-profile" name="profile_url" value="<?php echo (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add' && isset($_POST['profile_url'])) ? $_POST['profile_url'] : ''; ?>" /><br />
                <label for="source-feed">Feed URL:</label><br />
                <input type="text" id="source-feed" name="feed_url" value="<?php echo (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add' && isset($_POST['feed_url'])) ? $_POST['feed_url'] : ''; ?>" /><br />
                <input type="checkbox" id="source-lifestream" name="lifestream" <?php echo (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add' && isset($_POST['lifestream'])) ? 'checked' : ''; ?> />
                <label for="source-lifestream">Add to Lifestream</label><br />
                <input type="checkbox" id="source-status" name="updates" <?php echo (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add' && isset($_POST['updates'])) ? 'checked' : ''; ?> />
                <label for="source-status">Use in Status Box</label><br />
                <input type="submit" value="Add Source" />
                <input type="hidden" name="action" value="add" />
            </form>
        </div>
    </div>
<?php
}
?>
