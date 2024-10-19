<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UsersSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="users-search-toggle">
    <!-- Larger Toggle Button with Arrow aligned to the right -->
    <button id="toggle-search-form" class="btn btn-info btn-lg w-100 d-flex justify-content-between align-items-center">
    <span style="font-weight: bold;">Search</span> <!-- Bold Search Form Text -->
    <span id="toggle-arrow">&#9660;</span> <!-- Down arrow -->
</button>


    <!-- Card Layout -->
    <div id="search-form-container" class="card mt-3" style="display:none;">
        <!-- Card Header (Optional) -->

        <!-- Card Body -->
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'options' => [
                    'data-pjax' => 1,
                    'id' => 'search-form' // Assign an ID for JavaScript reference
                ],
            ]); ?>

            <!-- Adjust the form field sizes using Bootstrap grid and form-control-sm -->
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'id')->textInput(['placeholder' => 'Enter user ID', 'class' => 'form-control form-control-sm']) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'user_name')->textInput(['placeholder' => 'Enter username', 'class' => 'form-control form-control-sm']) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'first_name')->textInput(['placeholder' => 'Enter first name', 'class' => 'form-control form-control-sm']) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'last_name')->textInput(['placeholder' => 'Enter last name', 'class' => 'form-control form-control-sm']) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'mobile')->textInput(['placeholder' => 'Enter mobile number', 'class' => 'form-control form-control-sm']) ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary btn-sm']) ?>
                <!-- Reset button triggers a manual reset using JavaScript -->
                <?= Html::button(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary btn-sm', 'id' => 'reset-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<!-- JavaScript for toggling the form, arrow, and handling reset button -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var toggleButton = document.getElementById('toggle-search-form');
        var searchFormContainer = document.getElementById('search-form-container');
        var toggleArrow = document.getElementById('toggle-arrow');
        var resetButton = document.getElementById('reset-button');
        var searchForm = document.getElementById('search-form');

        // Toggle search form visibility and arrow direction
        toggleButton.addEventListener('click', function() {
            if (searchFormContainer.style.display === 'none' || searchFormContainer.style.display === '') {
                searchFormContainer.style.display = 'block'; // Show the form
                toggleArrow.innerHTML = '&#9650;'; // Up arrow
            } else {
                searchFormContainer.style.display = 'none';  // Hide the form
                toggleArrow.innerHTML = '&#9660;'; // Down arrow
            }
        });

        // Reset form fields and submit the form to show all records
        resetButton.addEventListener('click', function() {
            // Clear all text inputs
            searchForm.querySelectorAll('input[type="text"]').forEach(function(input) {
                input.value = ''; // Clear input fields
            });

            // Reset dropdowns and checkboxes if needed
            searchForm.querySelectorAll('select').forEach(function(select) {
                select.selectedIndex = 0; // Reset dropdowns to first option
            });
            searchForm.querySelectorAll('input[type="checkbox"], input[type="radio"]').forEach(function(field) {
                field.checked = false; // Reset checkboxes and radio buttons
            });

            // Submit the form with empty fields to reset the search and show all records
            searchForm.submit();
        });
    });
</script>
