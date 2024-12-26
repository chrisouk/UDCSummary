<?php

	/**
	 * This module will select all classmarks below a specified classmark
	 * and check/regenerate their hierarchy codes and levels, and produce warnings about
	 * missing broader categories 
	 * @copyright Chris Overfield 2012
	 */

    define("DEBUGSTRING", 1);

	class HierarchyData 
	{
		public	$id;
		public 	$tag;
		public 	$hierarchy_code;
		public	$hierarchy_level;
		public	$broader_category;
        public  $encoded_tag;
		public	$operation;
		public  $children;

		public function __construct()
		{
			$this->id = 0;
			$this->tag = "";
			$this->hierarchy_code = "";
			$this->hierarchy_level = 0;
			$this->broader_category = -1;
            $this->encoded_tag = "";
			$this->operation = "";
		}
	};
	
	class TreeNode
	{
		public $id;
		public $children;

		public function __construct()
		{
			$this->id = 0;
			$this->children = array();
		}
	};

    function DebugEcho($debugstring)
    {
        if (DEBUGSTRING == 1)
        {
            #echo $debugstring . "<br>\n";
        }
    }
	/**
	 * CreateHierarchyTree
	 * Iterate over the loaded records grouping classmarks with their parent classmarks (in order)
	 * @param array $records
	 * @param array $tree
	 */
	
	function CreateHierarchyTree(&$records, &$parents, &$all_records)
	{
        DebugEcho("Creating hierarchy tree");

		$success = true;

		# Create a tree of nodes - $parents will contain all nodes in the tree (flattened)
		# each node will contain the IDs of its children
		foreach($records as $record)
		{
			$node = new TreeNode();
			$node->id = $record->id;
			
			$broader_category = $record->broader_category;
			if ($broader_category == -1)
			{
				echo "No broader category for "	 . $record->tag . "\n";
				$success = false;
			}
					
			if (isset($parents[$broader_category]))
			{
				$parent =& $parents[$broader_category];
				array_push($parent->children, $node->id);
                if (strlen($record->tag > 2))
                {
                    if (substr($record->tag, 0, 3) == '24-')
                    {
                        $started_dump = false;
                        foreach($parent->children as $id)
                        {
                            if (isset($all_records[$id]))
                            {
                                if ($started_dump)
                                {
                                    #echo ", ";
                                }
                                $this_record = $all_records[$id];
                                #echo "[" . $this_record->tag . " (" . $this_record->id . ")]";
                                $started_dump = true;
                            }
                        }
                    }
                }
			}
            else
            {
                DebugEcho("No parents for broader " . $broader_category . " ({$record->tag} [{$record->id}])");
            }

            if (isset($all_records[$broader_category]))
            {
                $broad_record = $all_records[$broader_category];
                #DebugEcho(' Adding node ' . $node->id . "({$record->tag}) to {$broad_record->tag} tree");
            }

			$parents[$node->id] = $node;
		}
		
		return $success;
	}
		
	/**
	 * PopulateExistingHierarchy
	 * Extracts all classmark records starting with $tag (e.g. 51 will retrieve records with a tag matching 51%) 
	 * @param string $tag
	 */

	function PopulateExistingHierarchy($tag, &$sorted_records, &$records, &$tree)
	{
        DebugEcho('Populating the hierarchy for ' . $tag);

		# Fetch all records matching the supplied classmark tag
		$sql = 	"select c.classmark_id, c.classmark_tag, c.hierarchy_level, c.broader_category, c.classmark_enc_tag, h.hierarchy_code ".
				"from classmarks c left outer join classmark_hierarchy h on c.classmark_id = h.classmark_id ".
				"where c.active = 'Y' and c.classmark_tag like '" . mysql_real_escape_string($tag) . "%'".
				"order by c.classmark_enc_tag";

        DebugEcho($sql);

		$result = mysql_query($sql);
		if ($result)
		{
			while(($row = mysql_fetch_row($result)))
			{
				$record = new HierarchyData();
				$record->id = $row[0];
				$record->tag = $row[1];
				$record->hierarchy_level = $row[2];
				$record->broader_category = $row[3];
                $record->encoded_tag = $row[4];
                $record->hierarchy_code = $row[5];

                #DebugEcho("Found " . $record->tag . " (" . $record->id . ")");

				$records[$record->id] = $record;
                array_push($sorted_records, $record);
			}
			
			mysql_free_result($result);
		}
		
		if (count($sorted_records) == 0)
		{
			echo "No records match the supplied tag\n";
			return false;
		}
        else
        {
            DebugEcho(count($sorted_records) . " records loaded");
        }
		
		# Now construct the parent/child tree and iterate through it, assigning new hierarchy codes and levels where required
		$tree = array();
		if (!CreateHierarchyTree($sorted_records, $tree, $records))
		{
			echo "Please assign all missing broader categories\n";
			return false;
		}
		
		return true;
	}
	
	function IncrementHierarchyCode(array &$hierarchy_code_list)
	{
		# Increment the last component
		if (count($hierarchy_code_list) == 0)
			return;

        #DebugEcho("HC Was: " . implode('.', $hierarchy_code_list));

		$item_count = count($hierarchy_code_list);
		$last_component = $hierarchy_code_list[$item_count-1];
		$last_component++;
		$hierarchy_code_list[$item_count-1] = sprintf("%03d", $last_component);

        #DebugEcho("HC Now: " . implode('.', $hierarchy_code_list));

        return $last_component;
	}
	
	function TraverseRecord(&$node, &$records, &$tree, $hierarchy_level, $hierarchy_code, &$output_sql)
	{
		if ($node == null)
        {
            DebugEcho('No node supplied');
			return;
        }

		if (isset($records[$node->id]))
		{
			$record =& $records[$node->id];
		}
		else
        {
            DebugEcho('Record matching node id ' . $node->id . ' was not found');
            return;
        }

        echo "<tr><td bgcolor=\"white\">Traversing</td><td bgcolor=\"white\">" . $record->id . "</td><td bgcolor=\"white\">" . $record->tag . "</td><td bgcolor=\"white\">" . $record->hierarchy_level . "</td><td bgcolor=\"white\">{$record->broader_category}</td><td bgcolor=\"white\">{$record->hierarchy_code}</td><td bgcolor=\"white\">{$record->encoded_tag}</td>\n";

        # Is the hierarchy code empty?
		if (empty($record->hierarchy_code))
		{
			# Assign the current hierarchy code and mark the record for insert
            #DebugEcho("Adding: " . $hierarchy_code);
            echo "<td bgcolor=\"white\">Inserting {$hierarchy_code}</td>";
            $record->hierarchy_code = $hierarchy_code;
			array_push($output_sql, "insert into classmark_hierarchy (classmark_id, hierarchy_code) values (" . $record->id . ", '" . $hierarchy_code . "');");
		}
		else if ($hierarchy_code != $record->hierarchy_code)
		{
			# Assign the current hierarchy code and mark the record for update
            echo "<td bgcolor=\"white\">Updating to {$hierarchy_code}</td>";
            #DebugEcho("Updating: " . $hierarchy_code);
			$record->hierarchy_code = $hierarchy_code;
			array_push($output_sql, "update classmark_hierarchy set hierarchy_code = '" . $hierarchy_code . "' where classmark_id = " . $record->id . ";");
		}
		else
        {
            echo "<td bgcolor=\"white\">&nbsp;</td>";
            #DebugEcho("Match: " . $hierarchy_code);
        }

        if ($record->hierarchy_level != $hierarchy_level)
        {
            echo "<td bgcolor=\"white\">Hierarchy_level " . $record->hierarchy_level . " to " . $hierarchy_level . "</td>";
            $record->hierarchy_level = $hierarchy_level;
            array_push($output_sql, "update classmarks set hierarchy_level = " . $record->hierarchy_level . " where classmark_id = " . $record->id . ";");
        }
        else
        {
            echo "<td bgcolor=\"white\">&nbsp;</td>";
        }
        echo "</tr>\n";

		# Does the hierarchy level match?
		if ($record->hierarchy_level != $hierarchy_level)
		{
			$record->hierarchy_level = $hierarchy_level;
			array_push($output_sql, "update classmarks set hierarchy_level = " . $hierarchy_level . " where classmark_id = " . $record->id . ";");
		}
		
		# Process all children
		if (count($node->children) == 0)
			return;

        # Children are on the next level
		$hierarchy_level++;

        # We need to increment the hierarchy code to add another level for the children
        $hierarchy_code_list = explode(".", $hierarchy_code);
        array_push($hierarchy_code_list, "000");
        IncrementHierarchyCode($hierarchy_code_list);
        $hierarchy_code = implode(".", $hierarchy_code_list);

		foreach($node->children as $id)
		{
			if (isset($tree[$id]))
			{
				$childnode = $tree[$id];
				TraverseRecord($childnode, $records, $tree, $hierarchy_level, $hierarchy_code, $output_sql);
                $hierarchy_code_list = explode(".", $hierarchy_code);
                IncrementHierarchyCode($hierarchy_code_list);
                $hierarchy_code = implode(".", $hierarchy_code_list);
            }
		}
	}
	
	function OutputHierarchyTree(&$sorted_records, &$records, &$tree)
	{
        DebugEcho('Outputting hierarchy tree');

		# What's the top hierarchy code and level?
        $id = $sorted_records[0]->id;
		$top_code = $sorted_records[0]->hierarchy_code;
		$top_level = $sorted_records[0]->hierarchy_level;

        DebugEcho('Looking for id ' . $id . " (" . $top_code . ")");
		
		# Recurse through the tree assigning hierarchy codes as we go
		$output_sql = array();
		
		if (isset($tree[$id]))
		{
			$node = $tree[$id];
		}

        echo "<table bgcolor=\"#efefef\" cellpadding=\"5\" cellspacing=\"1\">\n";
		TraverseRecord($node, $records, $tree, $top_level, $top_code, $output_sql);
        echo "</table>\n";

        # Recurse through the tree outputting SQL for changed/inserted hierarchy data
		foreach($output_sql as $sql)
		{
			echo $sql . "<br>\n";
		}
	}

    include_once ('DBConnectInfo.php');

    $dbc = @mysql_connect (DBHOST, DBUSER, DBPASS) or die ('Could not connect to database: ' . mysql_error());
    mysql_select_db (DBDATABASE);
    mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $dbc);
    mysql_query("SET CHARACTER SET utf8");
    mysql_query("SET NAMES utf8");

    $tag = "1";

    if (isset($_GET['tag']))
    {
        $tag = $_GET['tag'];
    }

    # Initialise the record array
    $sorted_records = array();
	$records = array();
	$tree = array();
	
	if (PopulateExistingHierarchy($tag, $sorted_records, $records, $tree))
	{
		OutputHierarchyTree($sorted_records, $records, $tree);
    }

    DebugEcho('Finished');

    mysql_close($dbc);
?>