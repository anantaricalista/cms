<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">

    <section class="content">

        <div class="container-fluid">

            <?php if (validation_errors()): ?>
                <div class="alert alert-danger">
                    <?= validation_errors(); ?>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('admin/careers/update/' . $career['id']); ?>"
                  method="post">

                <?php $this->load->view('backend/careers/_form', [
                    'career' => $career
                ]); ?>

                <div class="card">

                    <div class="card-footer text-right">

                        <a href="<?= site_url('admin/careers'); ?>"
                           class="btn btn-secondary">
                            Batal
                        </a>

                        <button type="submit"
                                class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Update
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </section>

</div>
