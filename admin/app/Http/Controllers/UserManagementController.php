<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StaffAccount;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::all();
        $staff = StaffAccount::all();

        return view('admin.user_management', compact('users', 'staff'));
    }


    public function create()
    {
        return view('admin.user_create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'contact_number' => 'required|unique:users',
        'password' => 'required|string|min:6',
        'profile_picture' => 'nullable|image|max:2048',
    ]);

    $path = null;
    if ($request->hasFile('profile_picture')) {
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');
    }

    try {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'contact_number' => $request->contact_number,
            'profile_picture' => $path,
        ]);

        return redirect()->route('user_management')->with('success', 'User added successfully!');
    } catch (QueryException $e) {
        if ($e->errorInfo[1] == 1062) {
            return redirect()->back()->withInput()->with('error', 'Email or contact number already exists.');
        }
        return redirect()->back()->withInput()->with('error', 'Something went wrong while saving the user.');
    }
}

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user_edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6', // Allow null for password
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        // Only update the password if it is provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'contact_number' => $request->contact_number,
            'profile_picture' => $user->profile_picture,
        ]);

        return redirect()->route('user_management')->with('success', 'User updated successfully!');
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }
        $user->delete();

        return redirect()->route('user.management')->with('success', 'User deleted successfully!');
    }






    // Import Users from Excel
    public function uploadExcel(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        // Handle the file upload
        $file = $request->file('excel_file');
        $path = $file->storeAs('uploads', 'users.xlsx', 'public');

        // Load the Excel file
        $spreadsheet = IOFactory::load(storage_path('app/public/uploads/users.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        // Process each row and insert into the users table
        foreach ($data as $row) {
            // Ensure the row has the expected columns (adjust indexes based on your Excel format)
            if (!empty($row[0]) && !empty($row[1])) { // Adjust based on column positions
                User::create([
                    'name' => $row[0],  // Assuming the name is in the first column
                    'email' => $row[1], // Assuming email is in the second column
                    'address' => $row[2], // Adjust as per the data
                    'contact_number' => $row[3], // Adjust as per the data
                    'password' => Hash::make('defaultPassword'), // Add a default password or customize
                ]);
            }
        }

        return redirect()->route('user_management')->with('success', 'Users imported successfully!');
    }








    // registrar office account
    public function registrarStaff()
    {
        $staff = StaffAccount::where('roles', 2)->get();
        return view('admin.registrar_staff_account', compact('staff'));
    }

    // CREATE Registrar Staff
    public function createRegistrarStaff()
    {
        return view('admin.registrar_staff_create');
    }

    // STORE Registrar Staff
    public function storeRegistrarStaff(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff_accounts',
            'password' => 'required|string|min:6',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('staff_profiles', 'public');
        }

        StaffAccount::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles' => 2, // Registrar Staff
            'profile_picture' => $path,
        ]);

        return redirect()->route('registrar.staff')->with('success', 'Registrar Staff added successfully!');
    }

    // EDIT Registrar Staff
    public function editRegistrarStaff($id)
    {
        $staff = StaffAccount::findOrFail($id);
        return view('admin.registrar_staff_edit', compact('staff'));
    }

    // UPDATE Registrar Staff
    public function updateRegistrarStaff(Request $request, $id)
    {
        $staff = StaffAccount::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff_accounts,email,' . $id,
            'password' => 'nullable|string|min:6',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($staff->profile_picture) {
                Storage::disk('public')->delete($staff->profile_picture);
            }
            $path = $request->file('profile_picture')->store('staff_profiles', 'public');
            $staff->profile_picture = $path;
        }

        if ($request->filled('password')) {
            $staff->password = Hash::make($request->password);
        }

        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'profile_picture' => $staff->profile_picture,
        ]);

        return redirect()->route('registrar.staff')->with('success', 'Registrar Staff updated successfully!');
    }

    // DELETE Registrar Staff
    public function destroyRegistrarStaff($id)
    {
        $staff = StaffAccount::findOrFail($id);
        if ($staff->profile_picture) {
            Storage::disk('public')->delete($staff->profile_picture);
        }
        $staff->delete();

        return redirect()->route('registrar.staff')->with('success', 'Registrar Staff deleted successfully!');
    }









     // accounting office account
    public function accountingStaff()
    {
        $staff = StaffAccount::where('roles', 1)->get();
        return view('admin.accounting_staff_account', compact('staff'));
    }

    // CREATE Accounting Staff
    public function createAccountingStaff()
    {
        return view('admin.accounting_staff_create');
    }

    // STORE Accounting Staff
    public function storeAccountingStaff(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff_accounts',
            'password' => 'required|string|min:6',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('staff_profiles', 'public');
        }

        StaffAccount::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles' => 1, // Accounting Staff
            'profile_picture' => $path,
        ]);

        return redirect()->route('accounting.staff')->with('success', 'Accounting Staff added successfully!');
    }

    // EDIT Accounting Staff
    public function editAccountingStaff($id)
    {
        $staff = StaffAccount::findOrFail($id);
        return view('admin.accounting_staff_edit', compact('staff'));
    }

    // UPDATE Accounting Staff
    public function updateAccountingStaff(Request $request, $id)
    {
        $staff = StaffAccount::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff_accounts,email,' . $id,
            'password' => 'nullable|string|min:6',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($staff->profile_picture) {
                Storage::disk('public')->delete($staff->profile_picture);
            }
            $path = $request->file('profile_picture')->store('staff_profiles', 'public');
            $staff->profile_picture = $path;
        }

        if ($request->filled('password')) {
            $staff->password = Hash::make($request->password);
        }

        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'profile_picture' => $staff->profile_picture,
        ]);

        return redirect()->route('accounting.staff')->with('success', 'Accounting Staff updated successfully!');
    }

    // DELETE Accounting Staff
    public function destroyAccountingStaff($id)
    {
        $staff = StaffAccount::findOrFail($id);
        if ($staff->profile_picture) {
            Storage::disk('public')->delete($staff->profile_picture);
        }
        $staff->delete();

        return redirect()->route('accounting.staff')->with('success', 'Accounting Staff deleted successfully!');
    }











     // Admin Account
    public function adminAccount()
    {
        $staff = StaffAccount::where('roles', 0)->get();
        return view('admin.admin_account', compact('staff'));
    }

    // CREATE Admin
    public function createAdmin()
    {
        return view('admin.admin_create');
    }

    // STORE Admin
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff_accounts',
            'password' => 'required|string|min:6',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('staff_profiles', 'public');
        }

        StaffAccount::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles' => 0, // Admin Account
            'profile_picture' => $path,
        ]);

        return redirect()->route('admin.account')->with('success', 'Admin added successfully!');
    }

    // EDIT Admin
    public function editAdmin($id)
    {
        $staff = StaffAccount::findOrFail($id);
        return view('admin.admin_edit', compact('staff'));
    }

    // UPDATE Admin
    public function updateAdmin(Request $request, $id)
    {
        $staff = StaffAccount::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff_accounts,email,' . $id,
            'password' => 'nullable|string|min:6',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($staff->profile_picture) {
                Storage::disk('public')->delete($staff->profile_picture);
            }
            $path = $request->file('profile_picture')->store('staff_profiles', 'public');
            $staff->profile_picture = $path;
        }

        if ($request->filled('password')) {
            $staff->password = Hash::make($request->password);
        }

        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'profile_picture' => $staff->profile_picture,
        ]);

        return redirect()->route('admin.account')->with('success', 'Admin updated successfully!');
    }

    // DELETE Admin
    public function destroyAdmin($id)
    {
        $staff = StaffAccount::findOrFail($id);
        if ($staff->profile_picture) {
            Storage::disk('public')->delete($staff->profile_picture);
        }
        $staff->delete();

        return redirect()->route('admin.account')->with('success', 'Admin deleted successfully!');
    }

}
