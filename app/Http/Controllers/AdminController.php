use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function students()
    {
        $students = Student::with('muhdir')->get();
        return view('admin.students.index', compact('students'));
    }

    public function createStudent()
    {
        $muhdirs = User::where('role', 'muhdir')->get();
        return view('admin.students.create', compact('muhdirs'));
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'muhdir_id' => 'required|exists:users,id',
        ]);

        Student::create($request->all());

        return redirect()->route('admin.students.index')->with('success', 'تم إضافة الطالب وتكليفه بنجاح');
    }
}
