<?php
/**
 * Plugin Name: Об'єкт нерухомості
 * Description: Плагін для створення типу запису "Об'єкт нерухомості" та таксономії "Район".
 * Version: 1.0
 * Author: Artem Lyach
 * Author URI: https://lyachartem.com
 */

// Створення типу запису "Об'єкт нерухомості"
function create_real_estate_post_type() {
    $labels = array(
        'name'               => 'Об\'єкти нерухомості',
        'singular_name'      => 'Об\'єкт нерухомості',
        'menu_name'          => 'Нерухомість',
        'add_new'            => 'Додати новий',
        'add_new_item'       => 'Додати новий об\'єкт нерухомості',
        'edit_item'          => 'Редагувати об\'єкт нерухомості',
        'new_item'           => 'Новий об\'єкт нерухомості',
        'view_item'          => 'Переглянути об\'єкт нерухомості',
        'search_items'       => 'Пошук об\'єктів нерухомості',
        'not_found'          => 'Об\'єкти нерухомості не знайдені',
        'not_found_in_trash' => 'Об\'єкти нерухомості не знайдені в кошику',
        'parent_item_colon'  => 'Батьківський об\'єкт нерухомості:',
        'all_items'          => 'Всі об\'єкти нерухомості',
        'archives'           => 'Архіви об\'єктів нерухомості',
        'insert_into_item'   => 'Вставити в об\'єкт нерухомості',
        'uploaded_to_this_item' => 'Завантажено для цього об\'єкта нерухомості',
        'featured_image'        => 'Зображення об\'єкту нерухомості',
        'filter_items_list'     => 'Фільтрувати список об\'єктів нерухомості',
        'items_list_navigation' => 'Навігація по списку об\'єктів нерухомості',
        'items_list' => 'Список об\'єктів нерухомості',
        );
        $args = array(
          'labels'              => $labels,
          'public'              => true,
          'menu_position'       => 5,
          'menu_icon'           => 'dashicons-admin-home',
          'supports'            => array( 'title', 'editor', 'thumbnail' ),
          'taxonomies'          => array( 'district' ),
          'has_archive'         => true,
          'rewrite'             => array( 'slug' => 'real-estate' ),
      );
      
      register_post_type( 'real_estate', $args );
    }
    add_action( 'init', 'create_real_estate_post_type' );
    
    // Створення таксономії "Район"
    function create_district_taxonomy() {
    $labels = array(
    'name' => 'Райони',
    'singular_name' => 'Район',
    'search_items' => 'Пошук районів',
    'popular_items' => 'Популярні райони',
    'all_items' => 'Всі райони',
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => 'Редагувати район',
    'update_item' => 'Оновити район',
    'add_new_item' => 'Додати новий район',
    'new_item_name' => 'Назва нового району',
    'separate_items_with_commas' => 'Відокремлюйте райони комами',
    'add_or_remove_items' => 'Додати або видалити райони',
    'choose_from_most_used' => 'Обрати з найбільш вживаних районів',
    'not_found' => 'Райони не знайдені',
    'menu_name' => 'Райони',
    );
    $args = array(
      'labels'            => $labels,
      'public'            => true,
      'hierarchical'      => true,
      'show_admin_column' => true,
      'rewrite'           => array( 'slug' => 'district' ),
  );
  
  register_taxonomy( 'district', 'real_estate', $args );
}
add_action( 'init', 'create_district_taxonomy' );        

// 


function enqueue_jquery() {
    wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_jquery' );


// Шорткод для відображення блоку фільтра пошуку
function render_real_estate_search_form() {
    ob_start();
    ?>
    <form class="real-estate-search-form" method="get" action="">
        <div class="form-group">
            <label for="house_name">Назва будинку:</label>
            <input type="text" name="house_name" id="house_name" value="<?php echo esc_attr( $_GET['house_name'] ?? '' ); ?>">
        </div>
        <div class="form-group">
            <label for="location_coordinates">Координати розташування:</label>
            <input type="text" name="location_coordinates" id="location_coordinates" value="<?php echo esc_attr( $_GET['location_coordinates'] ?? '' ); ?>">
        </div>
        <div class="form-group">
            <label for="search_number_of_floors">Кількість поверхів:</label>
            <select name="search_number_of_floors" id="search_number_of_floors">
                <option value="">Виберіть кількість поверхів</option>
                <?php for ($i = 1; $i <= 20; $i++) : ?>
                    <option value="<?php echo $i; ?>" <?php selected( $_GET['search_number_of_floors'], $i ); ?>><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Тип будівлі:</label>
            <div>
                <input type="radio" id="building-type-panel" name="type_of_building" value="Панель" <?php checked( $_GET['type_of_building'], 'Панель' ); ?>>
                <label for="building-type-panel">Панель</label>
            </div>
            <div>
                <input type="radio" id="building-type-brick" name="type_of_building" value="Цегла" <?php checked( $_GET['type_of_building'], 'Цегла' ); ?>>
                <label for="building-type-brick">Цегла</label>
            </div>
            <div>
                <input type="radio" id="building-type-block" name="type_of_building" value="Піноблок" <?php checked( $_GET['type_of_building'], 'Піноблок' ); ?>>
                <label for="building-type-block">Піноблок</label>
            </div>
        </div>
        <div class="form-group">
            <input type="submit" value="Пошук" id="real-estate-search-submit">
        </div>
    </form>

    <script>
        jQuery(document).ready(function($) {
            $('#real-estate-search-submit').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                var data = form.serialize();
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'GET',
                    data: data + '&action=real_estate_search',
                    beforeSend: function() {
                        $('#real-estate-search-results').html('Завантаження результатів...');
                    },
                    success: function(response) {
                        $('#real-estate-search-results').html(response);
                    },
                    error: function(xhr, status, error) {
                        $('#real-estate-search-results').html('Сталася помилка під час виконання запиту.');
                    }
                });
            });
        });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode( 'real_estate_search', 'render_real_estate_search_form' );





function filter_real_estate_results() {
    if (isset($_GET['action']) && $_GET['action'] == 'real_estate_search') {
        $meta_query = array('relation' => 'AND');

        // Додавання умов для фільтрації по полях ACF
        if (!empty($_GET['house_name'])) {
            $meta_query[] = array(
                'key'     => 'house_name',
                'value'   => $_GET['house_name'],
                'compare' => 'LIKE',
            );
        }

        if (!empty($_GET['location_coordinates'])) {
            $meta_query[] = array(
                'key'     => 'location_coordinates',
                'value'   => $_GET['location_coordinates'],
                'compare' => 'LIKE',
            );
        }

        if (!empty($_GET['search_number_of_floors'])) {
            $meta_query[] = array(
                'key'     => 'number_of_floors',
                'value'   => $_GET['search_number_of_floors'],
                'compare' => '=',
            );
        }

        if (!empty($_GET['type_of_building'])) {
            $meta_query[] = array(
                'key'     => 'type_of_building',
                'value'   => $_GET['type_of_building'],
                'compare' => '=',
            );
        }


        
   // Отримання номеру сторінки з параметру paged
   $paged = isset($_GET['paged']) ? intval($_GET['paged']) : 1;

   $query_args = array(
       'post_type'      => 'real_estate',
       'posts_per_page' => 3,
       'meta_query'     => $meta_query,
       'paged'          => $paged, // Використовуємо номер сторінки для пагінації
   );
        
       

        $query = new WP_Query($query_args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                // Виведення результатів пошуку
               //  the_title('<h2>', '</h2>');
               //  the_content();
               //  echo '<hr>';
                echo '<div style="position: relative; padding: 45px; border: 1px solid #000; margin-bottom: 5px;">';
                $house_name = get_field('house_name');
                $location_coordinates = get_field('location_coordinates');
                $number_of_floors = get_field('number_of_floors');
                $type_of_building = get_field('type_of_building');
                echo $house_name . '<br>';
                echo $location_coordinates . '<br>';
                echo $number_of_floors . '<br>';
                echo $type_of_building . '<br>';

                // Виведення таксономії "Район"
                $district_terms = get_the_terms(get_the_ID(), 'district');
                if ($district_terms && !is_wp_error($district_terms)) {
                    echo 'Район: ';
                    foreach ($district_terms as $term) {
                        echo $term->name . ', ';
                    }
                }


                echo '</div>';


            }
            wp_reset_postdata();

            // Виведення пагінації
            $total_pages = $query->max_num_pages;
            if ($total_pages > 1) {
                $current_page = max(1, $paged);
                $pagination_args = array(
                    'base'               => esc_url_raw(add_query_arg('paged', '%#%')),
                    'format'             => '',
                    'total'              => $total_pages,
                    'current'            => $current_page,
                    'show_all'           => false,
                    'end_size'           => 1,
                    'mid_size'           => 2,
                    'prev_next'          => true,
                    'prev_text'          => __('« Previous'),
                    'next_text'          => __('Next »'),
                    'type'               => 'plain',
                    'add_args'           => false,
                    'add_fragment'       => '',
                    'before_page_number' => '',
                    'after_page_number'  => '',
                );
                echo '<div class="pagination">';
                echo paginate_links($pagination_args);
                echo '</div>';

                // Додаємо скрипт для обробки пагінації через Ajax
                ?>
                <script>
                    jQuery(function($) {
                        $('.pagination a').on('click', function(e) {
                            e.preventDefault();
                            var page = $(this).attr('href').split('paged=')[1];
                            var data = {
                                action: 'real_estate_search',
                                paged: page,
                                // Додаткові параметри фільтрації, якщо необхідно
                                // house_name: $('#house_name').val(),
                                // location_coordinates: $('#location_coordinates').val(),
                                // search_number_of_floors: $('#search_number_of_floors').find(':selected').val(),
                                // type_of_building: $('#type_of_building').val()



                            };
                            $.get('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
                                // $('#real_estate_results').html(response);
                                $('#real-estate-search-results').html(response);
                                history.pushState(null, null, $(this).attr('href')); // Оновлення URL у браузері

                            });
                        });
                    });
                </script>
                <?php
            }

            
        } else {
            echo 'Нічого не знайдено';
        }

        exit; // Завершення виконання скрипту після виведення результатів
    }
}
add_action('wp_ajax_real_estate_search', 'filter_real_estate_results');
add_action('wp_ajax_nopriv_real_estate_search', 'filter_real_estate_results');


// 























// Віджет для відображення результатів пошуку
class RealEstateSearchWidget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'real_estate_search_widget',
            'Real Estate Search Widget',
            array( 'description' => 'Displays a real estate search form and results' )
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        
        // Отримання атрибутів віджета
        $title = apply_filters( 'widget_title', $instance['title'] );

        // Виведення заголовка віджета
        if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // Виведення форми пошуку
        echo $this->render_real_estate_search_form();

        echo '<div id="real-estate-search-results-widget"></div>';

        echo $args['after_widget'];
    }

    public function form( $instance ) {
        // Виведення форми налаштувань в адмінці
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        // Оновлення налаштувань віджета
        $instance = array();
        $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';

        return $instance;
    }

    public function render_real_estate_search_form() {
        ob_start();
        ?>
<form class="real-estate-search-form-widget" method="get" action="">
        <div class="form-group">
            <label for="house_name">Назва будинку:</label>
            <input type="text" name="house_name" id="house_name" value="<?php echo esc_attr( $_GET['house_name'] ?? '' ); ?>">
        </div>
        <div class="form-group">
            <label for="location_coordinates">Координати розташування:</label>
            <input type="text" name="location_coordinates" id="location_coordinates" value="<?php echo esc_attr( $_GET['location_coordinates'] ?? '' ); ?>">
        </div>
        <div class="form-group">
            <label for="search_number_of_floors">Кількість поверхів:</label>
            <select name="search_number_of_floors" id="search_number_of_floors">
                <option value="">Виберіть кількість поверхів</option>
                <?php for ($i = 1; $i <= 20; $i++) : ?>
                    <option value="<?php echo $i; ?>" <?php selected( $_GET['search_number_of_floors'], $i ); ?>><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Тип будівлі:</label>
            <div>
                <input type="radio" id="building-type-panel" name="type_of_building" value="Панель" <?php checked( $_GET['type_of_building'], 'Панель' ); ?>>
                <label for="building-type-panel">Панель</label>
            </div>
            <div>
                <input type="radio" id="building-type-brick" name="type_of_building" value="Цегла" <?php checked( $_GET['type_of_building'], 'Цегла' ); ?>>
                <label for="building-type-brick">Цегла</label>
            </div>
            <div>
                <input type="radio" id="building-type-block" name="type_of_building" value="Піноблок" <?php checked( $_GET['type_of_building'], 'Піноблок' ); ?>>
                <label for="building-type-block">Піноблок</label>
            </div>
        </div>
        <div class="form-group">
            <input type="submit" value="Пошук" id="real-estate-search-submit">
        </div>
    </form>

        <script>
            jQuery(document).ready(function($) {
                // Змінна для зберігання поточної сторінки
                var currentPage = 1;

                // Перехоплення події подачі форми
                $('.real-estate-search-form-widget').submit(function(event) {
                    event.preventDefault();

                    // Отримання значень полів форми
                    var formData = $(this).serialize();

                    // Додавання номеру поточної сторінки до даних форми
                    formData += '&paged=' + currentPage;

                    // AJAX-запит на обробку форми
                    $.ajax({
                        type: 'GET',
                        url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                        data: formData + '&action=real_estate_search_widget',
                        beforeSend: function() {
                            // Відображення завантажувача або індікатора процесу
                            $('#real-estate-search-results-widget').html('Loading...');
                        },
                        success: function(response) {
                            // Виведення результатів
                            $('#real-estate-search-results-widget').html(response);
                        },
                        error: function() {
                            // Обробка помилки
                            $('#real-estate-search-results-widget').html('An error occurred.');
                        }
                    });
                });

                // AJAX-пагінація
                $(document).on('click', '.real-estate-search-pagination a', function(event) {
                    event.preventDefault();

                    // Отримання номеру сторінки з атрибута даних
                    currentPage = $(this).data('page');

                    // Відправка AJAX-запиту з номером нової сторінки
                    $('.real-estate-search-form-widget').submit();
                });
            });
        </script>
        <?php
        return ob_get_clean();
    }
}

function register_real_estate_search_widget() {
    register_widget( 'RealEstateSearchWidget' );
}
add_action( 'widgets_init', 'register_real_estate_search_widget' );

function filter_real_estate_results_widget() {
    if (isset($_GET['action']) && $_GET['action'] == 'real_estate_search_widget') {
        $meta_query = array('relation' => 'AND');

        // Додавання умов для фільтрації по полях ACF
        if (!empty($_GET['house_name'])) {
            $meta_query[] = array(
                'key'     => 'house_name',
                'value'   => $_GET['house_name'],
                'compare' => 'LIKE',
            );
        }

        if (!empty($_GET['location_coordinates'])) {
            $meta_query[] = array(
                'key'     => 'location_coordinates',
                'value'   => $_GET['location_coordinates'],
                'compare' => 'LIKE',
            );
        }

        if (!empty($_GET['search_number_of_floors'])) {
            $meta_query[] = array(
                'key'     => 'number_of_floors',
                'value'   => $_GET['search_number_of_floors'],
                'compare' => '=',
            );
        }

        if (!empty($_GET['type_of_building'])) {
            $meta_query[] = array(
                'key'     => 'type_of_building',
                'value'   => $_GET['type_of_building'],
                'compare' => '=',
            );
        }


        
   // Отримання номеру сторінки з параметру paged
   $paged = isset($_GET['paged']) ? intval($_GET['paged']) : 1;

   $query_args = array(
       'post_type'      => 'real_estate',
       'posts_per_page' => 3,
       'meta_query'     => $meta_query,
       'paged'          => $paged, // Використовуємо номер сторінки для пагінації
   );
        
       

        $query = new WP_Query($query_args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                // Виведення результатів пошуку
               //  the_title('<h2>', '</h2>');
               //  the_content();
               //  echo '<hr>';
                echo '<div style="position: relative; padding: 45px; border: 1px solid #000; margin-bottom: 5px;">';
                $house_name = get_field('house_name');
                $location_coordinates = get_field('location_coordinates');
                $number_of_floors = get_field('number_of_floors');
                $type_of_building = get_field('type_of_building');
                echo $house_name . '<br>';
                echo $location_coordinates . '<br>';
                echo $number_of_floors . '<br>';
                echo $type_of_building . '<br>';

                // Виведення таксономії "Район"
                $district_terms = get_the_terms(get_the_ID(), 'district');
                if ($district_terms && !is_wp_error($district_terms)) {
                    echo 'Район: ';
                    foreach ($district_terms as $term) {
                        echo $term->name . ', ';
                    }
                }


                echo '</div>';


            }
            wp_reset_postdata();

                   // Виведення пагінації
                   $total_pages = $query->max_num_pages;
                   if ($total_pages > 1) {
                       $current_page = max(1, $paged);
                       $pagination_args = array(
                           'base'               => esc_url_raw(add_query_arg('paged', '%#%')),
                           'format'             => '',
                           'total'              => $total_pages,
                           'current'            => $current_page,
                           'show_all'           => false,
                           'end_size'           => 1,
                           'mid_size'           => 2,
                           'prev_next'          => true,
                           'prev_text'          => __('« Previous'),
                           'next_text'          => __('Next »'),
                           'type'               => 'plain',
                           'add_args'           => false,
                           'add_fragment'       => '',
                           'before_page_number' => '',
                           'after_page_number'  => '',
                       );
                       echo '<div class="pagination-widget">';
                       echo paginate_links($pagination_args);
                       echo '</div>';
       
                       // Додаємо скрипт для обробки пагінації через Ajax
                       ?>
                       <script>
                           jQuery(function($) {
                               $('.pagination-widget a').on('click', function(e) {
                                   e.preventDefault();
                                   var page = $(this).attr('href').split('paged=')[1];
                                   var data = {
                                       action: 'real_estate_search',
                                       paged: page,
                                       // Додаткові параметри фільтрації, якщо необхідно
                                       // house_name: $('#house_name').val(),
                                       // location_coordinates: $('#location_coordinates').val(),
                                       // search_number_of_floors: $('#search_number_of_floors').find(':selected').val(),
                                       // type_of_building: $('#type_of_building').val()
       
       
       
                                   };
                                   $.get('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
                                       // $('#real_estate_results').html(response);
                                       $('#real-estate-search-results-widget').html(response);
                                       history.pushState(null, null, $(this).attr('href')); // Оновлення URL у браузері
       
                                   });
                               });
                           });
                       </script>
                       <?php
                   }

            
        } else {
            echo 'Нічого не знайдено';
        }

        exit; // Завершення виконання скрипту після виведення результатів
        wp_die();
    }
}
add_action( 'wp_ajax_real_estate_search_widget', 'filter_real_estate_results_widget' );
add_action( 'wp_ajax_nopriv_real_estate_search_widget', 'filter_real_estate_results_widget' );
