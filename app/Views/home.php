<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<section class="hero">
    <h1>Power Your Future with Solar</h1>
    <p>Family-run and locally grown, we have spent over a decade helping homeowners and businesses switch to clean, dependable solar power, from the first site visit to lifelong maintenance.</p>
    <a class="btn" href="<?= site_url('register') ?>">Create your account</a>
</section>

<section id="services" class="block">
    <h2>Services</h2>
    <div class="cards">
        <div class="card"><h3>Consultation</h3><p>Site assessment, system design and permitting.</p></div>
        <div class="card"><h3>Installation</h3><p>Panels, inverters, batteries, racking and wiring installed right.</p></div>
        <div class="card"><h3>Maintenance</h3><p>Repair and monitoring to keep your system performing.</p></div>
    </div>
</section>

<section id="about" class="block alt">
    <h2>About Company</h2>
    <div class="cards">
        <div class="card"><h3>Cost savings</h3><p>Lower your monthly electricity bills.</p></div>
        <div class="card"><h3>Sustainability</h3><p>Cleaner energy for the next generation.</p></div>
        <div class="card"><h3>Energy independence</h3><p>Less reliance on the grid.</p></div>
    </div>
</section>

<section id="support" class="block">
    <h2>Support</h2>
    <p>Questions about your account or your system? Register to reach our team.</p>
</section>
<?= $this->endSection() ?>
