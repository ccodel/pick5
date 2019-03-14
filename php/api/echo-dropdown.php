<?php

echo '<select id="session-dropdown" name="session-dropdown">
                    <option value="blank" <?php if (!isset($_SESSION["info"]["session"])) echo "selected=\'selected\'" ?> >------</option>
                    <option value="session1" <?php if ($_SESSION["info"]["session"] == "session1") echo "selected=\'selected\'" ?>>1 - Kickoff Game</option>
                    <option value="session2" <?php if ($_SESSION["info"]["session"] == "session2") echo "selected=\'selected\'" ?>>2 - Week 1</option>
                    <option value="session3" <?php if ($_SESSION["info"]["session"] == "session3") echo "selected=\'selected\'" ?>>3 - Week 2</option>
                    <option value="session4" <?php if ($_SESSION["info"]["session"] == "session4") echo "selected=\'selected\'" ?>>4 - Week 3</option>
                    <option value="session5" <?php if ($_SESSION["info"]["session"] == "session5") echo "selected=\'selected\'" ?>>5 - Week 4</option>
                    <option value="session6" <?php if ($_SESSION["info"]["session"] == "session6") echo "selected=\'selected\'" ?>>6 - Week 5</option>
                    <option value="session7" <?php if ($_SESSION["info"]["session"] == "session7") echo "selected=\'selected\'" ?>>7 - Week 6</option>
                    <option value="session8" <?php if ($_SESSION["info"]["session"] == "session8") echo "selected=\'selected\'" ?>>8 - Week 7</option>
                    <option value="session9" <?php if ($_SESSION["info"]["session"] == "session9") echo "selected=\'selected\'" ?>>9 - Week 8</option>
                    <option value="session10" <?php if ($_SESSION["info"]["session"] == "session10") echo "selected=\'selected\'" ?>>10 - Week 9</option>
                    <option value="session11" <?php if ($_SESSION["info"]["session"] == "session11") echo "selected=\'selected\'" ?>>11 - Week 10</option>
                    <option value="session12" <?php if ($_SESSION["info"]["session"] == "session12") echo "selected=\'selected\'" ?>>12 - Week 11</option>
                    <option value="session13" <?php if ($_SESSION["info"]["session"] == "session13") echo "selected=\'selected\'" ?>>13 - Week 12</option>
                    <option value="session14" <?php if ($_SESSION["info"]["session"] == "session14") echo "selected=\'selected\'" ?>>14 - Thanksgiving Day</option>
                    <option value="session15" <?php if ($_SESSION["info"]["session"] == "session15") echo "selected=\'selected\'" ?>>15 - Week 13</option>
                    <option value="session16" <?php if ($_SESSION["info"]["session"] == "session16") echo "selected=\'selected\'" ?>>16 - Week 14</option>
                    <option value="session17" <?php if ($_SESSION["info"]["session"] == "session17") echo "selected=\'selected\'" ?>>17 - Week 15</option>
                    <option value="session18" <?php if ($_SESSION["info"]["session"] == "session18") echo "selected=\'selected\'" ?>>18 - Week 16</option>
                    <option value="session19" <?php if ($_SESSION["info"]["session"] == "session19") echo "selected=\'selected\'" ?>>19 - Week 17</option>
                    <option value="session20" <?php if ($_SESSION["info"]["session"] == "session20") echo "selected=\'selected\'" ?>>20 - Wild Card Playoffs</option>
                    <option value="session21" <?php if ($_SESSION["info"]["session"] == "session21") echo "selected=\'selected\'" ?>>21 - Divisional Playoffs</option>
                    <option value="session22" <?php if ($_SESSION["info"]["session"] == "session22") echo "selected=\'selected\'" ?>>22 - Conference Championships</option>
                    <option value="session23" <?php if ($_SESSION["info"]["session"] == "session23") echo "selected=\'selected\'" ?>>23 - Superbowl</option>
                </select>';

?>