<?php

global $myirgCustomImportJson;
global $wpdb;

?>

<div class="wrap">
    <h2>JSON Import</h2>
    <div class="irgcustomimportjson_admin_container">
        <div class="irgcustomimportjson_div">
            <h3>Parse new JSON</h3>

            <input type="text" style="width: 100%;" placeholder="Enter your JSON url" id="jsonurl"/>
            <button class="irgcustomimportjson_btn" style="width: 100%;" id="parsejsonbutton"> PARSE NOW</button>

            <div id="jsonimportload" style="width: 100%; text-align: center; margin-top: 20px; display: none;">
                <div class="lds-ellipsis">Please wait<br/>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>

            <div id="jsonimportsucc" style="width: 100%; text-align: center; margin-top: 20px; display: none;">
                <h2 style="color: green;">DONE!</h2>
            </div>

            <div id="jsonimporterr" style="width: 100%; text-align: center; margin-top: 20px; display: none;">
                <h2 style="color: red;">ERROR!</h2>
            </div>

            <h3>Past json parsed</h3>

            <?php $results = $myirgCustomImportJson->irgCustomImportJson_show_past_parsed(); ?>
            <?php if (count($results) == 0) {
                echo '<p>You didn`t parsed anything yet!</p>';
            } else { ?>
                <table border="0" width="100%">
                    <tr style="font-weight: bold;">
                        <td>#ID</td>
                        <td>URL</td>


                        <td>&nbsp;</td>
                    </tr>

                    <?php

                    foreach ($results as $r) {
                        echo '<tr>
                    <td>' . $r['id'] . '</td>
                    <td>' . $r['feedurl'] . '</td>
                    <td></td>
                    </tr>';
                    }
                    ?>

                </table>
            <?php } ?>
        </div>

    </div>
</div>