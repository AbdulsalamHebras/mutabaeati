use App\Models\Student;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function create(Student $student)
    {
        // Ensure student belongs to the authenticated muhdir
        if ($student->muhdir_id !== auth()->id()) {
            abort(403);
        }

        return view('muhdir.reports.create', compact('student'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB
            'month' => 'required|string',
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        $student = Student::findOrFail($request->student_id);
        if ($student->muhdir_id !== auth()->id()) {
            abort(403);
        }

        $path = $request->file('file')->store('reports', 'public');

        Report::create([
            'student_id' => $request->student_id,
            'muhdir_id' => auth()->id(),
            'title' => $request->title,
            'file_path' => $path,
            'month' => $request->month,
            'year' => $request->year,
        ]);

        return redirect()->route('muhdir.dashboard')->with('success', 'تم رفع التقرير بنجاح');
    }
}
