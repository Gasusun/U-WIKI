<?php

/* =========================================================
   LẤY KẾT QUẢ RANKING
========================================================= */


/*
    Tổng số lần xuất hiện của mỗi item
*/

$sql = "

SELECT

    r.id,

    r.name,

    r.image,


    COUNT(m.id) AS total_matches,


    SUM(
        CASE
            WHEN m.selected_item_id = r.id
            THEN 1
            ELSE 0
        END
    ) AS wins,


    SUM(
        CASE
            WHEN m.selected_item_id IS NOT NULL
            THEN 1
            ELSE 0
        END
    ) AS total_votes


FROM ranking_items r


LEFT JOIN ranking_session_matches m

    ON (
        m.item_a_id = r.id
        OR
        m.item_b_id = r.id
    )


LEFT JOIN ranking_sessions s

    ON s.id = m.session_id

    AND s.status = 'completed'


WHERE r.active = 1


GROUP BY
    r.id,
    r.name,
    r.image

ORDER BY
    wins DESC,
    total_votes DESC,
    r.name ASC
";


$result = $conn->query($sql);


$ranking = [];


while ($row = $result->fetch_assoc()) {

    $totalVotes =
        intval($row['total_votes']);

    $wins =
        intval($row['wins']);


    /*
        WIN RATIO

        Ví dụ:
        thắng 80 / 100 lần
        = 80%
    */

    if ($totalVotes > 0) {

        $winRatio =
            ($wins / $totalVotes) * 100;

    } else {

        $winRatio = 0;

    }


    $row['win_ratio'] =
        $winRatio;


    $ranking[] = $row;
}


/*
    Tính tổng Win Ratio
    để tính Final Win Ratio
*/

$totalWinRatio = 0;


foreach ($ranking as $item) {

    $totalWinRatio +=
        $item['win_ratio'];
}


/*
    Hiển thị bảng
*/

?>


<?php if (empty($ranking)): ?>

    <div class="empty-ranking">

        Chưa có dữ liệu bình chọn.

    </div>

<?php else: ?>


<div class="results-wrapper">


    <!-- HEADER -->

    <div class="results-header">

        <div>#</div>

        <div>Media</div>

        <div>Name</div>

        <div>Win Ratio</div>

    </div>


    <?php

    $rank = 1;

    foreach ($ranking as $item):


        /*
            FINAL WIN RATIO

            Công thức:

            Win Ratio của item
            -------------------
            Tổng Win Ratio

            × 100
        */

        if ($totalWinRatio > 0) {

            $finalRatio =
                (
                    $item['win_ratio']
                    /
                    $totalWinRatio
                ) * 100;

        } else {

            $finalRatio = 0;

        }


        /*
            Giới hạn 100%
        */

        $finalRatio =
            min(100, $finalRatio);

    ?>


    <div class="result-row">


        <!-- RANK -->

        <div class="result-number">

            <?= $rank ?>

        </div>


        <!-- IMAGE -->

        <div class="result-media">

            <img
                src="<?= htmlspecialchars(
                    $item['image']
                ) ?>"
                alt=""
            >

        </div>


        <!-- NAME -->

        <div class="result-name">

            <?= htmlspecialchars(
                $item['name']
            ) ?>

        </div>


        <!-- WIN RATIO -->

        <div>

            <div class="ratio-box">

                <div
                    class="ratio-fill"
                    style="
                        width:
                        <?= number_format(
                            $item['win_ratio'],
                            2,
                            '.',
                            ''
                        ) ?>%;
                    "
                >

                    <?= number_format(
                        $item['win_ratio'],
                        1
                    ) ?>%

                </div>

            </div>

        </div>
    

    </div>


    <?php

        $rank++;

    endforeach;

    ?>


</div>


<?php endif; ?>