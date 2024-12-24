<?php

use App\Models\Booking;
use App\Models\Education;
use App\Models\Eductaion;
use App\Models\Experience;
use App\Models\HomePage;
use App\Models\PersonalInformation;
use App\Models\Service;
use App\Models\Skill;
use App\Models\Testimonial;
use App\Models\Project;
use App\Models\Property;
use App\Models\User;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Spatie\Permission\Models\Role;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('dashboard'));
});

// Home > Dashboard
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Dashboard', route('dashboard'));
});

// Home > Dashboard > User Management
Breadcrumbs::for('user-management.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('User Management', route('user-management.users.index'));
});

// Home > Dashboard > User Management > Users
Breadcrumbs::for('user-management.users.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user-management.index');
    $trail->push('Users', route('user-management.users.index'));
});

// Home > Dashboard > User Management > Users > [User]
Breadcrumbs::for('user-management.users.show', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('user-management.users.index');
    $trail->push(ucwords($user->name), route('user-management.users.show', $user));
});

// Home > Dashboard > User Management > Roles
Breadcrumbs::for('user-management.roles.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user-management.index');
    $trail->push('Roles', route('user-management.roles.index'));
});

// Home > Dashboard > User Management > Roles > [Role]
Breadcrumbs::for('user-management.roles.show', function (BreadcrumbTrail $trail, Role $role) {
    $trail->parent('user-management.roles.index');
    $trail->push(ucwords($role->name), route('user-management.roles.show', $role));
});

// Home > Dashboard > User Management > Permission
Breadcrumbs::for('user-management.permissions.index', function (BreadcrumbTrail $trail) {
    $trail->parent('user-management.index');
    $trail->push('Permissions', route('user-management.permissions.index'));
});

// Home > Dashboard > Property Management
Breadcrumbs::for('property-management.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Property Management', route('property-management.properties.index'));
});

// Home > Dashboard > Property Management > Properties
Breadcrumbs::for('property-management.properties.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Properties', route('property-management.properties.index'));
});

// Home > Dashboard > Property Management > Properties > [Property]
Breadcrumbs::for('property-management.properties.show', function (BreadcrumbTrail $trail, Property $property) {
    $trail->parent('property-management.properties.index');
    $trail->push(ucwords($property->name), route('property-management.properties.show', $property));
});


// Home > Dashboard > Booking Management
Breadcrumbs::for('booking-management.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Booking Management', route('booking-management.bookings.index'));
});

// Home > Dashboard > Booking Management > Properties
Breadcrumbs::for('booking-management.bookings.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Properties', route('booking-management.bookings.index'));
});

// Home > Dashboard > Booking Management > Properties > [Booking]
Breadcrumbs::for('booking-management.bookings.show', function (BreadcrumbTrail $trail, Booking $booking) {
    $trail->parent('booking-management.bookings.index');
    $trail->push(ucwords($booking->name), route('booking-management.bookings.show', $booking));
});
