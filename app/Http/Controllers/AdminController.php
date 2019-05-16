<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function admin()
    {
        return response()->json([
            'message' => 'Welcome administrator',
        ]);
    }

    // Admin nu merge cum trebuie, trebuie sa sterg middleware-ul de admin care l-am facut, sa mai modific prin controllere, adica sa mut ce trebuie de la toti userii doar la admin, de asemenea trebuie si sa sterg chestiile alea de register si login facut cu php arisan make:auth, apoi trebuie sa ma apuc de client. Cred ca in client voi verifica cine a admin si cine nu, chiar daca asta poate nu e bine, dar nu stiu cum sa fac in backend chestia asta.
}
