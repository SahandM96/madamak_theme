
<?php
    $current_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    if (isset($_GET['styles'])) {
        $styles = $_GET['styles'];
    }

    if (isset($_GET['platform'])) {
        $platform = $_GET['platform'];
    }

    if (isset($_GET['companies'])) {
        $companies = $_GET['companies'];
    }

    if (isset($_GET['product_cat'])) {
        $product_cat = $_GET['product_cat'];
    }

    if (isset($_GET['min_price'])) {
        $min_price = $_GET['min_price'];
    }

    if (isset($_GET['max_price'])) {
        $max_price = $_GET['max_price'];
    }

    if (isset($_GET['orderby'])) {
        $orderby = $_GET['orderby'];
    }

?>

<section id="product-filters">

    <form action="" method="get" id="filter-form">

        <div class="filters-header" onclick="openAllFilter(this)">

            <h6>فیلتر ها</h6>
            <div class="fh" onclick="openFilter(this,'category')">
                <p>دسته‌بندی</p>
                <i class="ti-angle-down"></i>
            </div>
            <div class="fh" onclick="openFilter(this,'styles')">
                <p>سبک</p>
                <i class="ti-angle-down"></i>
            </div>
            <div class="fh" onclick="openFilter(this,'companies')">
                <p>کمپانی سازنده</p>
                <i class="ti-angle-down"></i>
            </div>
            <div class="fh" onclick="openFilter(this,'price')">
                <p>قیمت</p>
                <i class="ti-angle-down"></i>
            </div>
            <div class="fh" onclick="openFilter(this,'order')">
                <p>مرتب سازی</p>
                <i class="ti-angle-down"></i>
            </div>

            <span class="ti-angle-down filtersButton"></span>

        </div>

        <div class="filters">

            <!-- CATEGORY FILTER -->
            <div class="category-filter filter">
                <ul class="itemList" id="category-ul">
                    <li class="items-title">دسته‌بندی ها:</li>
                    <?php
                        $terms = get_terms(array(
                            'taxonomy' => 'product_cat',
                            'hide_empty' => false,
                        ));
                        if ($terms){
                            $count = 1;
                            foreach ($terms as $term) { ?>
                                <li>
                                    <input type="radio" name="product_cat" id="category<?php echo $count; ?>" value="<?php echo $term->slug; ?>" class="category-radio"  onclick="catChange(this)"
                                        <?php 
                                        if (!empty($product_cat)) {
                                            if ($product_cat == $term->slug) echo 'checked';
                                        }
                                        $cate = get_queried_object();
                                        if ($cate->slug == $term->slug) echo ' checked';;
                                        ?>
                                    >
                                    <label for="category<?php echo $count; ?>"><?php echo $term->name; ?></label>
                                </li>
                            <?php
                                $count++;
                            }
                        }
                    ?>
                </ul>
            </div>
            <!-- CATEGORY FILTER -->


            <!-- GAME STYLE FILTER -->
            <div class="styles-filter filter">
                <ul class="itemList">
                    <li class="items-title">سبک ها:</li>
                    <?php
                        $terms = get_terms(array(
                            'taxonomy' => 'styles',
                            'hide_empty' => false,
                        ));
                        if ($terms){
                            $count = 1;
                            foreach ($terms as $term) { ?>
                                <li>
                                    <input type="checkbox" name="styles[<?php echo $count; ?>]" id="style<?php echo $count; ?>" value="<?php echo $term->slug; ?>" 
                                        <?php 
                                        if (!empty($styles)) {
                                            $key = array_search($term->slug,$styles);
                                            if ($styles[$key] == $term->slug) echo 'checked';
                                        }
                                        $cate = get_queried_object();
                                        if ($cate->slug == $term->slug) echo ' checked';
                                        ?>
                                    >
                                    <label for="style<?php echo $count; ?>"><?php echo $term->name; ?></label>
                                </li>
                            <?php
                                $count++;
                            }
                        }
                    ?>
                </ul>
            </div>
            <!-- GAME STYLE FILTER -->



            <!-- GAME STYLE FILTER -->
            <div class="styles-filter filter">
                <ul class="itemList">
                    <li class="items-title">پلتفرم ها:</li>
                    <?php
                        $terms = get_terms(array(
                            'taxonomy' => 'platform',
                            'hide_empty' => false,
                        ));
                        if ($terms){
                            $count = 1;
                            foreach ($terms as $term) { ?>
                                <li>
                                    <input type="checkbox" name="platform[<?php echo $count; ?>]" id="platform<?php echo $count; ?>" value="<?php echo $term->slug; ?>" 
                                        <?php 
                                        if (!empty($platform)) {
                                            $key = array_search($term->slug,$platform);
                                            if ($platform[$key] == $term->slug) echo 'checked';
                                        }
                                        $cate = get_queried_object();
                                        if ($cate->slug == $term->slug) echo ' checked';
                                        ?>
                                    >
                                    <label for="platform<?php echo $count; ?>"><?php echo $term->name; ?></label>
                                </li>
                            <?php
                                $count++;
                            }
                        }
                    ?>
                </ul>
            </div>
            <!-- GAME STYLE FILTER -->



            <!-- COMPANY FILTER -->
            <div class="companies-filter filter">
                <ul class="itemList">
                <li class="items-title">کمپانی ها:</li>
                    <?php
                        $terms = get_terms(array(
                            'taxonomy' => 'companies',
                            'hide_empty' => false,
                        ));
                        if ($terms){
                            $count = 1;
                            foreach ($terms as $term) { ?>
                                <li>
                                    <input type="checkbox" name="companies[<?php echo $count; ?>]" id="company<?php echo $count; ?>" value="<?php echo $term->slug; ?>" 
                                        <?php 
                                            if (!empty($companies)) {
                                                $key = array_search($term->slug,$companies);
                                                if ($companies[$key] == $term->slug) echo 'checked';
                                            }
                                            $cate = get_queried_object();
                                            if ($cate->slug == $term->slug) echo ' checked';
                                        ?>
                                    >
                                    <label for="company<?php echo $count; ?>"><?php echo $term->name; ?></label>
                                </li>
                            <?php
                                $count++;
                            }
                        }
                    ?>
                </ul>
            </div>
            <!-- COMPANY FILTER -->




            <!-- PRICE FILTER -->
            <div class="price-filter filter">

                <p id="min-price"><?php echo (!empty($min_price)) ? 'posted' : get_min_max_price()[0]; ?></p>
                <p id="max-price"><?php echo (!empty($min_price)) ? 'posted' : get_min_max_price()[1]; ?></p>
                <p id="selected-min-price"><?php echo (!empty($min_price)) ? $min_price : get_min_max_price()[0]; ?></p>
                <p id="selected-max-price"><?php echo (!empty($max_price)) ? $max_price : get_min_max_price()[1]; ?></p>
                <p id="siteurl"><?php echo get_site_url(); ?></p>
                
                <div id="slider-range"></div>
                <input type="text" name="min_price" id="minInput" readonly>
                <input type="text" name="max_price" id="maxInput" readonly>

                <p id="price-log"></p>
                
            </div>
            <!-- PRICE FILTER -->




            <!-- ORDER FILTER -->
            <div class="order-filter filter">
                <ul class="itemList">
                    <li>
                        <input type="radio" name="orderby" id="order1" value="menu_order" <?php if ($orderby == 'menu_order') echo 'checked'; ?>>
                        <label for="order1">مرتب سازی پیش‌فرض</label>
                    </li>
                    <li>
                        <input type="radio" name="orderby" id="order2" value="popularity" <?php if ($orderby == 'popularity') echo 'checked'; ?>>
                        <label for="order2">مرتب سازی بر اساس محبوبیت</label>
                    </li>
                    <li>
                        <input type="radio" name="orderby" id="order3" value="date" <?php if ($orderby == 'date') echo 'checked'; ?>>
                        <label for="order3">مرتب سازی بر اساس زمان انتشار</label>
                    </li>
                    <li>
                        <input type="radio" name="orderby" id="order4" value="price" <?php if ($orderby == 'price') echo 'checked'; ?>>
                        <label for="order4">مرتب سازی بر اساس ارزانترین</label>
                    </li>
                    <li>
                        <input type="radio" name="orderby" id="order5" value="price-desc" <?php if ($orderby == 'price-desc') echo 'checked'; ?>>
                        <label for="order5">مرتب سازی بر اساس گرانترین</label>
                    </li>
                </ul>
            </div>
            <!-- ORDER FILTER -->


            <div class="name-filter filter">
                <label for="name-input" class="items-title">نام محصول:</label>
                <input type="text" name="s" id="name-input" placeholder="چیزی تایپ کنید...">
            </div>

            <button type="submit" id="applyFilters">فیلتر</button>

        </div>

    </form>

</section>